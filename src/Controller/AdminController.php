<?php

namespace App\Controller;

use GuzzleHttp\Client;
use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Resultat;
use App\Form\CommuneType;
use App\Entity\BureauVote;
use App\Form\ProvinceType;
use App\Entity\Departement;
use App\Controller\MockData;
use App\Entity\TempResultat;
use App\Form\BureauVoteType;
use App\Form\UploadFileType;
use App\Form\DepartementType;
use App\Controller\ExcelConnector;
use App\Entity\User;
use App\Form\ManagerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminController extends AbstractController
{
    #[Route('/manager', name: 'app_manager')]
    public function index(ManagerRegistry $manager): Response
    {
        $datas=$manager->getRepository(TempResultat::class)->findBy(["etat"=>0]);
        return $this->render('admin/manager.html.twig', [
            'datas'=>$datas,
        ]);
    }

    #[Route('/admin/resultat', name:'resultat_admin')]
    public function resultatAdmin(ManagerRegistry $manager): Response
    {
        return $this->render('resultat/table.html.twig', [
            'resultat'=>$manager->getRepository(Resultat::class)->findBy(["etat" =>5]),
        ]);
    }

    #[Route('/admin/provinces/', name:'resultat_dept')]
    public function resultatDept(): Response{
        return $this->render('admin/tableDepartement.html.twig', []);
    }

    #[Route('/admin/departements/', name:'resultat_commune')]
    public function resultatCommune(): Response{
        return $this->render('admin/tableCommune.html.twig', []);
    }

    #[Route('/admin/communes/', name: 'communeList')]
    public function communeList(): Response
    {
        return $this->render('admin/communeList.html.twig', [
            
        ]);
    }

    #[Route('/admin/resultat/ajouter/form', name: 'add_resultat')]
    public function addResult(): Response
    {
        $form=$this->createForm(ManagerType::class);
        return $this->render('admin/formAddResult.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    //verification

    #[Route('/manager/pv/{id}/soumission', name: 'check')]
    public function verification(?int $id,ManagerRegistry $manager,Request $request): Response
    {
        $pv= $manager->getRepository(TempResultat::class)->findOneBy(["id"=> $id]);
        $form=$this->createForm(ManagerType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $resultat = new Resultat();
            $resultat->setCode($pv->getCodeKobo());
            $resultat->setVotant($form["votant"]->getData());
            $resultat->setSuffrageExprime($form["suffrageExp"]->getData());
            $resultat->setSuffrageNul($form["suffrageNul"]->getData());
            $resultat->setVoteOui($form["voteOui"]->getData());
            $resultat->setVoteNon($form["voteNon"]->getData());
            $resultat->setProcesVerbal($pv->getProcesVerbal());

            $bv=$manager->getRepository(BureauVote::class)->findOneBy(["code"=> $form["bureauVote"]->getData()]);
            if($bv != null){
                $resultat->setBureauVote($bv);
                $resultat->setEtat(3);
            } else{
                $resultat->setEtat(2);
            }
            $pv->setEtat(1);
            $manager->getManager()->persist($resultat);
            $manager->getManager()->persist($pv);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_manager");
        }
        return $this->render('admin/check.html.twig', [
           'form'=> $form->createView(),
           'pv'=> $pv,
        ]);
    }

    #[Route('/admin/resultat/{id}/valider', name: 'valider')]
    public function validation(?int $id,ManagerRegistry $manager): Response
    {
        $pv= $manager->getRepository(TempResultat::class)->findOneBy(["id"=>$id]);
        if($pv !=null){
            $resultat = new Resultat();
            $resultat->setCode($pv->getCodeKobo());
            $resultat->setVotant($pv->getNombreVotant());
            $resultat->setSuffrageExprime($pv->getSuffrageExprime());
            $resultat->setProcesVerbal("null");
            $resultat->setVoteNon($pv->getVoteNon());
            $resultat->setVoteOui($pv->getVoteOui());
            $resultat->setSuffrageNul($pv->getBulletinNuls());
            $resultat->setBureauVote($manager->getRepository(BureauVote::class)->findOneBy(["code"=>$pv->getBureauVote()]));
            $resultat->setEtat(2);
            $manager->getManager()->persist($resultat);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_manager");
        }

        return $this->render('admin/check.html.twig', []);
    }


    //synchro

    #[Route('/admin/sync', name:'synchro')]
    public function synchro(ManagerRegistry $manager): Response
    {
        $inDBdata=$manager->getRepository(TempResultat::class)->findAll();
        $kobo = new KoboConnector("004998ce52dc528dd4d1c5045ea702816aa9bb68");
        $myData= $kobo->findAll('https://kf.kobotoolbox.org/api/v2/assets/aAdvkva68zop3jWUBZXpux/data.json');
        
        foreach($myData["results"] as $d){
            $k=1;
            foreach($inDBdata as $dbdata){
                if($dbdata->getCodeKobo() == $d["_id"]){
                    $k=0;
                }
            }
            if($k==1){
                $temp = new TempResultat();
                $temp->setCodeKobo($d["_id"]);
                $temp->setProvince($d["province"]);
                $temp->setCommune($d["commune"]);
                $temp->setProcesVerbal($kobo->downloadImg($d["_attachments"][0]["download_url"]));
                $temp->setDate(new \DateTime($d["_submission_time"]));
                $temp->setEtat(0);
                $manager->getManager()->persist($temp);
                $manager->getManager()->flush();
            }
        }
        //dd($myData);
        return $this->redirectToRoute("app_manager");
    }

    // app superviseur
    #[Route('/superviseur', name:'app_superviseur')]
    public function index_superviseur(ManagerRegistry $manager): Response{
        return $this->render('admin/superviseur.html.twig', [
            'bvs'=> $manager->getRepository(BureauVote::class)->findAll(),
        ]);
    }

    #[Route('/superviseur/bureau-vote/{id}', name:'detailsBV')]
    public function consolider(?int $id,ManagerRegistry $manager):Response{
        $bvs=$manager->getRepository(Resultat::class)->findBy(["bureauVote" =>$manager->getRepository(BureauVote::class)->findOneBy(["id"=>$id])]);
        $filtred = array();
        $output=array();
        for($i=0;$i<count($bvs);$i++){
            $temp=array();
            for($j=0;$j<count($bvs);$j++){
                if(Utils::comparerResultat($bvs[$i],$bvs[$j])){
                    $temp[]=$bvs[$j];
                }
            }
            $filtred[]=$temp;
        }
        foreach(array_unique($filtred,SORT_REGULAR) as $f){
            $output[]=["total"=>count($f),"element"=>$f[0]]; 
        }
        return $this->render('admin/consol.html.twig',[
            'datas' => $output,
        ]);
    }

    #[Route('/superviseur/{id}/consolider', name:'consolider')]
    public function consol(?int $id,ManagerRegistry $manager):Response
    {
        $bv=$manager->getRepository(Resultat::class)->findOneBy(["id"=>$id]);
        $all=$manager->getRepository(Resultat::class)->findBy(["bureauVote"=>$bv->getBureauVote()]);
        if($bv){
            $bv->setEtat(5);
            foreach($all as $a){
                if($a->getId() !=$bv->getId()){
                    $a->setEtat(4);
                    $manager->getManager()->persist($a);
                    $manager->getManager()->flush();
                }
            }
            $manager->getManager()->persist($bv);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_superviseur");
        }

       return $this->redirectToRoute("app_superviseur");
    }

    //administration

    #[Route('/admin/administration', name:'administration')]
    public function admin(ManagerRegistry $manager ): Response
    {
        return $this->render('admin/admin.html.twig',[
            'province' =>count($manager->getRepository(Province::class)->findAll()),
            'dep'=>count($manager->getRepository(Departement::class)->findAll()),
            'com'=>count($manager->getRepository(Commune::class)->findAll()),
            'user' => count($manager->getRepository(User::class)->findAll()),
            'bv' =>count($manager->getRepository(BureauVote::class)->findAll()),
            'pvs' =>count($manager->getRepository(TempResultat::class)->findAll()),
            'pvv'=>count($manager->getRepository(Resultat::class)->findAll()),
        ]);
    }

    #[Route('/admin/resultat-valide', name:'pvValide')]
    public function pvValider(ManagerRegistry $manager): Response
    {
        return $this->render('admin/pv.html.twig',[
            'pvs'=> $manager->getRepository(Resultat::class)->findAll(),
        ]);
    }

    #[Route('/admin/resultat-soumis', name:'pvSoumis')]
    public function pvSoumis(ManagerRegistry $manager): Response
    {
        return $this->render('admin/soumissionView.html.twig',[
            'pvs'=> $manager->getRepository(TempResultat::class)->findAll(),
        ]);
    }

    //administration province


    #[Route('/admin/province', name:'app_province')]
    public function listProvince(ManagerRegistry $manager): Response{
        return $this->render('admin/tableProvince.html.twig',[
            'provinces'=> $manager->getRepository(Province::class)->findAll(),
        ]);
    }
    #[Route('/admin/province/new', name:'add_province')]
    #[Route('/admin/province/{id}/update', name:'update_province')]
    public function addOrUpdateProvince(?int $id,ManagerRegistry $manager,Request $request): Response{
        $province = new Province();
        if($id != null){
            $province=$manager->getRepository(Province::class)->findOneBy(['id'=> $id]);
        }
        $form= $this->createForm(ProvinceType::class, $province);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->getManager()->persist($province);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_province');
        }
        return $this->render('admin/formProvince.html.twig',[
            'form'=> $form->createView(),
            'editState'=> $province->getId()===null,
        ]);
    }
    #[Route('/admin/province/{id}/delete', name:'del_province')]
    public function deleteProvince(?int $id, ManagerRegistry $manager): Response{
        if($id !=null){
            $province=$manager->getRepository(Province::class)->findOneBy(['id'=> $id]);
            $manager->getManager()->remove($province);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_province');
        }
        return $this->redirectToRoute('app_province');
    }

    //administration departement

    #[Route('/admin/departement', name:'app_dept')]
    public function listDepartement(ManagerRegistry $manager): Response{
        return $this->render('admin/tableDepartement.html.twig',[
            'depts'=> $manager->getRepository(Departement::class)->findAll(),
        ]);
    }
    #[Route('/admin/departement/new', name:'add_dept')]
    #[Route('/admin/departement/{id}/update', name:'update_dept')]
    public function addOrUpdateDept(?int $id,ManagerRegistry $manager,Request $request): Response{
        $dept = new Departement();
        if($id != null){
            $dept =$manager->getRepository(Departement::class)->findOneBy(['id'=> $id]);
        }
        $form= $this->createForm(DepartementType::class, $dept);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->getManager()->persist($dept);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_dept');
        }
        return $this->render('admin/formDepartement.html.twig',[
            'form'=> $form->createView(),
            'editState'=> $dept->getId()===null,
        ]);
    }
    #[Route('/admin/departement/{id}/delete', name:'del_dept')]
    public function deleteDept(?int $id, ManagerRegistry $manager): Response{
        if($id !=null){
            $dept=$manager->getRepository(Departement::class)->findOneBy(['id'=> $id]);
            $manager->getManager()->remove($dept);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_dept');
        }
        return $this->redirectToRoute('app_dept');
    }

    //administration commune

    #[Route('/admin/commune', name:'app_comm')]
    public function listCommune(ManagerRegistry $manager): Response{
        return $this->render('admin/tableCommune.html.twig',[
            'comms'=> $manager->getRepository(Commune::class)->findAll(),
        ]);
    }
    #[Route('/admin/commune/new', name:'add_comm')]
    #[Route('/admin/commune/{id}/update', name:'update_comm')]
    public function addOrUpdateComm(?int $id,ManagerRegistry $manager,Request $request): Response{
        $comm = new Commune();
        if($id != null){
            $comm =$manager->getRepository(Commune::class)->findOneBy(['id'=> $id]);
        }
        $form= $this->createForm(CommuneType::class, $comm);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->getManager()->persist($comm);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_comm');
        }
        return $this->render('admin/formCommune.html.twig',[
            'form'=> $form->createView(),
            'editState'=> $comm->getId()===null,
        ]);
    }
    #[Route('/admin/commune/{id}/delete', name:'del_comm')]
    public function deleteCommune(?int $id, ManagerRegistry $manager): Response{
        if($id !=null){
            $comm=$manager->getRepository(Commune::class)->findOneBy(['id'=> $id]);
            $manager->getManager()->remove($comm);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_comm');
        }
        return $this->redirectToRoute('app_comm');
    }

    //administration bureau Vote

    #[Route('/admin/bureau-vote', name:'app_bv')]
    public function listBv(ManagerRegistry $manager): Response{
        return $this->render('admin/tableBureauVote.html.twig',[
            'bvs'=> $manager->getRepository(BureauVote::class)->findAll(),
        ]);
    }
    #[Route('/admin/bureau-vote/new', name:'add_bv')]
    #[Route('/admin/bureau-vote/{id}/update', name:'update_bv')]
    public function addOrUpdateBV(?int $id,ManagerRegistry $manager,Request $request): Response{
        $bv = new BureauVote();
        if($id != null){
            $bv =$manager->getRepository(BureauVote::class)->findOneBy(['id'=> $id]);
        }
        $form= $this->createForm(BureauVoteType::class, $bv);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager->getManager()->persist($bv);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_bv');
        }
        return $this->render('admin/formBv.html.twig',[
            'form'=> $form->createView(),
            'editState'=> $bv->getId()===null,
        ]);
    }
    #[Route('/admin/bureau-vote/{id}/delete', name:'del_bv')]
    public function deleteBv(?int $id, ManagerRegistry $manager): Response{
        if($id !=null){
            $bv=$manager->getRepository(BureauVote::class)->findOneBy(['id'=> $id]);
            $manager->getManager()->remove($bv);
            $manager->getManager()->flush();
            return $this->redirectToRoute('app_bv');
        }
        return $this->redirectToRoute('app_bv');
    }

    // importation de data
    #[Route('/admin/import', name:'import')]
    public function import(Request $request, ManagerRegistry $manager): Response
    {
        $form = $this->createForm(UploadFileType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){  
            
            $fichier = $form->get('fichier')->getData();
            $originalFilename = pathinfo($fichier->getClientOriginalName(), PATHINFO_FILENAME);
            $newFilename = $originalFilename . '.' . $fichier->guessExtension();
            try {
                $fichier->move(
                    $this->getParameter('fichier_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $mfile = new File($this->getParameter('fichier_directory') . '/' . $newFilename);
            if($form->get('choice')->getData() == "localite"){
                ExcelConnector::ImportGeoData($mfile, $manager);
            }
            if ($form->get('choice')->getData() == "resultat") {
                ExcelConnector::ImportResultat($mfile, $manager);
            }
        }
        
        return $this->render('admin/upload.html.twig',[
            'form'=> $form->createView(),
        ]);
    }

    //test boum
    #[Route('/test', name:'test')]
    public function test(Request $request, ManagerRegistry $manager)
    {
        
        dd("done");
    }
}
