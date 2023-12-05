<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\TestType;
use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Resultat;
use App\Form\CommuneType;
use App\Form\ManagerType;
use App\Form\ProvinceType;
use App\Entity\Departement;
use App\Entity\ResultatKobo;
use App\Form\UploadFileType;
use App\Form\DepartementType;
use App\Entity\ResultatOperateur;
use App\Controller\ExcelConnector;
use App\Entity\ResultatSuperviseur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class AdminController extends AbstractController
{
    #[Route('/manager', name: 'app_manager')]
    public function index(ManagerRegistry $manager): Response
    {
        $datas=$manager->getRepository(ResultatKobo::class)->findBy(["etat"=>0]);
        return $this->render('admin/manager.html.twig', [
            'datas'=>$datas,
        ]);
    }

    #[Route('/admin/resultat', name:'resultat_admin')]
    public function resultatAdmin(ManagerRegistry $manager): Response
    {
        return $this->render('resultat/table.html.twig', [
           // 'resultat'=>$manager->getRepository(Resultat::class)->findBy(["etat" =>5]),
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

    //verification

   #[Route('/manager/pv/{id}/', name: 'check')]
    public function verification(?int $id,ManagerRegistry $manager,Request $request): Response
    {
       $pv= $manager->getRepository(ResultatKobo::class)->findOneBy(["id"=> $id]);
        return $this->render('admin/check.html.twig', [
           'pv'=> $pv,
        ]);
    }

    #[Route('/manager/saisie', name:'saisie')]
    public function saisieOperateur(Request $request, ManagerRegistry $manager)
    {
        if($request->isMethod("POST")){
            $pv=$manager->getRepository(ResultatKobo::class)->findOneBy(["id"=>$request->request->all()['idPv']]);
            $commune=$manager->getRepository(Commune::class)->findOneBy(["id"=>$request->request->all()['commune']]);
            $resultat = new ResultatOperateur();
            $resultat->setCode($pv->getCodeKobo());
            $resultat->setCodeBureau($request->request->all()['codeBureau']);
            $resultat->setVotant($request->request->all()['votant']);
            $resultat->setSuffrageExprime($request->request->all()['suffrageExprime']);
            $resultat->setSuffrageNul($request->request->all()['suffrageNul']);
            $resultat->setVoteOui($request->request->all()['voteOui']);
            $resultat->setVoteNon($request->request->all()['voteNon']);
            $resultat->setImagePv($pv->getImagePv());
            $resultat->setCommune( $commune);
            $resultat->setEtat(0);
            $resultat->setCreatedAt(new \DateTimeImmutable);
            $resultat->setAutor($manager->getRepository(User::class)->findOneBy(["id"=>$this->getUser()->getId()]));
            $pv->setEtat(1);
            $manager->getManager()->persist($resultat);
            $manager->getManager()->persist($pv);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_manager");
        } 
    }
   

    //synchro

    #[Route('/admin/sync', name:'synchro')]
    public function synchro(ManagerRegistry $manager): Response
    {
        $inDBdata=$manager->getRepository(ResultatKobo::class)->findAll();
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
                $temp = new ResultatKobo();
                $temp->setCodeKobo($d["_id"]);
                $temp->setImagePv($kobo->downloadImg($d["_attachments"][0]["download_url"]));
                $temp->setDateSubmit(new \DateTime($d["_submission_time"]));
                $temp->setEtat(0);
                $manager->getManager()->persist($temp);
                $manager->getManager()->flush();
            }
        }
        return $this->redirectToRoute("app_manager");
    }

    // app superviseur
    #[Route('/superviseur', name:'app_superviseur')]
    public function index_superviseur(ManagerRegistry $manager): Response{
        return $this->render('admin/superviseur.html.twig', [
           'bvs'=> $manager->getRepository(ResultatOperateur::class)->findBy(["etat"=>0]),
        ]);
    }

    #[Route('/superviseur/pv/{id}', name:'detailsPV')]
    public function verifier(?int $id,ManagerRegistry $manager):Response{
        return $this->render('admin/detailsPv.html.twig',[
           'pv' => $manager->getRepository(ResultatOperateur::class)->findOneBy(["id"=>$id,"etat"=>0]),
        ]);
    }

    #[Route('/validation', name:'supValider')]
    public function handleData(Request $request,ManagerRegistry $manager) :Response
    {
        if($request->isMethod("POST")){
            $pv=$manager->getRepository(ResultatOperateur::class)->findOneBy(["id"=>$request->request->all()['idPv']]);
            $commune=$manager->getRepository(Commune::class)->findOneBy(["id"=>$request->request->all()['commune']]);
            $resultat = new ResultatSuperviseur();
            $resultat->setCode($pv->getCode());
            $resultat->setCodeBureau($request->request->all()['codeBureau']);
            $resultat->setVotant($request->request->all()['votant']);
            $resultat->setSuffrageExprime($request->request->all()['suffrageExprime']);
            $resultat->setSuffrageNul($request->request->all()['suffrageNul']);
            $resultat->setVoteOui($request->request->all()['voteOui']);
            $resultat->setVoteNon($request->request->all()['voteNon']);
            $resultat->setImagePv($pv->getImagePv());
            $resultat->setCommune($commune);
            $resultat->setAutor($pv->getAutor());
            $resultat->setCreatedAt($pv->getCreatedAt());
            $resultat->setValidator($manager->getRepository(User::class)->findOneBy(["id"=>$this->getUser()->getId()]));
            $resultat->setEtat(0);
            $resultat->setValidedOn(new \DateTimeImmutable);
            $pv->setEtat(1);
            $manager->getManager()->persist($resultat);
            $manager->getManager()->persist($pv);
            $manager->getManager()->flush();

            $existant=$manager->getRepository(Resultat::class)->findOneBy(["codeBureau"=>$pv->getCodeBureau()]);
            if($existant != null){
                return $this->redirectToRoute("checkSup",["code"=>$existant->getCodeBureau(),"com"=>$existant->getCommune()->getId()]);
            } else{
                $resdef = new Resultat();
                $resdef->setCode($pv->getCode());
                $resdef->setCodeBureau($request->request->all()['codeBureau']);
                $resdef->setVotant($request->request->all()['votant']);
                $resdef->setSuffrageExprime($request->request->all()['suffrageExprime']);
                $resdef->setSuffrageNul($request->request->all()['suffrageNul']);
                $resdef->setVoteOui($request->request->all()['voteOui']);
                $resdef->setVoteNon($request->request->all()['voteNon']);
                $resdef->setImagePv($pv->getImagePv());
                $resdef->setCommune($commune);
                $resdef->setEtat(0);
                $resdef->setCreatedAt($pv->getCreatedAt());
                $resdef->setAutor($pv->getAutor());
                $resdef->setValidator($resultat->getValidator());
                $resdef->setValidedOn($resultat->getValidedOn());
                $resultat->setEtat(2);
                $manager->getManager()->persist($resultat);
                $manager->getManager()->persist($resdef);
                $manager->getManager()->flush();
                return $this->redirectToRoute("app_superviseur");
            }
        }
        return $this->redirectToRoute("app_superviseur");
    }

    #[Route('/superviseur/pv/{id}/invalider', name:'invaliderPV')]
    public function invalider(?int $id,ManagerRegistry $manager):Response{
        $pv=$manager->getRepository(ResultatOperateur::class)->findOneBy(["id"=>$id]);
        if($pv){
            $pv->setEtat(2);
            $manager->getManager()->persist($pv);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_superviseur");
        }
        return $this->redirectToRoute("app_superviseur");
    }



    #[Route('/superviseur/{code}/{com}/consolidation', name:'checkSup')]
    public function consol(?string $code,?int $com,ManagerRegistry $manager):Response
    {
        $commune=$manager->getRepository(Commune::class)->findOneBy(["id"=>$com]);
        $actuel=$manager->getRepository(Resultat::class)->findOneBy(["codeBureau"=>$code,"commune"=>$commune]);
        $new=$manager->getRepository(ResultatSuperviseur::class)->findOneBy(["codeBureau"=>$code,"etat"=>0,"commune"=>$commune]);
        $other=$manager->getRepository(ResultatSuperviseur::class)->findBy(["codeBureau"=>$code,"etat"=>1,"commune"=>$commune]);
        //dd($other);
        return $this->render('admin/checkSup.html.twig',[
            'actuel'=>$actuel,
            'new'=>$new,
            'other'=>$other,
         ]);
    }

    #[Route('/superviseur/{id}/consolider', name:'consolider')]
    public function consolider(?int $id,ManagerRegistry $manager):Response
    {
        $new=$manager->getRepository(ResultatSuperviseur::class)->findOneBy(["id"=>$id]);
        if($new){
            $new->setEtat(1);
            $manager->getManager()->persist($new);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_superviseur");
        }
        return $this->redirectToRoute("app_superviseur");
    }

    #[Route('/superviseur/{id}/changer', name:'changerResultat')]
    public function changerResultat(?int $id,ManagerRegistry $manager):Response
    {
        $new=$manager->getRepository(ResultatSuperviseur::class)->findOneBy(["id"=>$id]);
        $actuel=$manager->getRepository(Resultat::class)->findOneBy(["codeBureau"=>$new->getCodeBureau(),"commune"=>$new->getCommune()]);
        if($actuel){
            $actuel->setSuffrageExprime($new->getSuffrageExprime());
            $actuel->setSuffrageNul($new->getSuffrageNul());
            $actuel->setVotant($new->getVotant());
            $actuel->setVoteOui($new->getVoteOui());
            $actuel->setVoteNon($new->getVoteNon());
            $actuel->setImagePv($new->getImagePv());
            $new->setEtat(2);
            $manager->getManager()->persist($actuel);
            $manager->getManager()->flush();
            //possiblite de faire un save back ici
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
           'bv' =>0,
            'pvs' =>0,
            'pvv'=>0,
        ]);
    }

    #[Route('/admin/resultat-valide', name:'pvValide')]
    public function pvValider(ManagerRegistry $manager): Response
    {
        return $this->render('admin/pv.html.twig',[
           // 'pvs'=> $manager->getRepository(Resultat::class)->findAll(),
        ]);
    }

    #[Route('/admin/resultat-soumis', name:'pvSoumis')]
    public function pvSoumis(ManagerRegistry $manager): Response
    {
        return $this->render('admin/soumissionView.html.twig',[
           // 'pvs'=> $manager->getRepository(TempResultat::class)->findAll(),
        ]);
    }

    //ajax provinces
    #[Route('/provinces', name:'get_province')]
    public function listProvince(ManagerRegistry $manager): Response{
        $raws =$manager->getRepository(Province::class)->findAll();
        $data=array();
        foreach($raws as $r){
            $data[]=["id"=>$r->getId(),"libelle"=>$r->getLibelle()];
        }
        return new JsonResponse(json_encode($data));
    }

   
    //ajax departement

    #[Route('/departements', name:'get_dept')]
    public function listDepartement(ManagerRegistry $manager,Request $request): Response{
        $data=array();
        if($request->isXmlHttpRequest() && $request->isMethod("POST")){
            $depts =$manager->getRepository(Departement::class)->findBy(["province"=>$request->request->all()['id']]);
            foreach($depts as $d){
                $data[]=["id"=>$d->getId(),"libelle"=>$d->getLibelle(),"parent"=>$d->getProvince()->getId()];
            }
            return new JsonResponse(json_encode($data));
        }
        return new JsonResponse(json_encode($data));
    }
    
    //ajax commune
    #[Route('/communes', name:'get_com')]
    public function listCommune(ManagerRegistry $manager,Request $request): Response{
        $data=array();
        if($request->isXmlHttpRequest() && $request->isMethod("POST")){
            $comms= $manager->getRepository(Commune::class)->findBy(["departement"=>$request->request->all()['id']]);
            foreach($comms as $d){
                $data[]=["id"=>$d->getId(),"libelle"=>$d->getLibelle(),"parent"=>$d->getDepartement()->getId()];
            }
            return new JsonResponse(json_encode($data));
        }
        return new JsonResponse(json_encode($data));
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
            if ($form->get('choice')->getData() == "user") {
                //  ExcelConnector::ImportResultat($mfile, $manager);
              }
            if ($form->get('choice')->getData() == "resultat") {
              //  ExcelConnector::ImportResultat($mfile, $manager);
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
       // dd($request->request->all());
       /* if($request->isMethod("POST")){
            $pv=$manager->getRepository(ResultatKobo::class)->findOneBy(["id"=>$request->request->all()['idPv']]);
            $commune=$manager->getRepository(Commune::class)->findOneBy(["id"=>$request->request->all()['commune']]);
            $resultat = new ResultatOperateur();
            $resultat->setCode($pv->getCodeKobo());
            $resultat->setCodeBureau($request->request->all()['codeBureau']);
            $resultat->setVotant($request->request->all()['votant']);
            $resultat->setSuffrageExprime($request->request->all()['suffrageExprime']);
            $resultat->setSuffrageNul($request->request->all()['suffrageNul']);
            $resultat->setVoteOui($request->request->all()['voteOui']);
            $resultat->setVoteNon($request->request->all()['voteNon']);
            $resultat->setImagePv($pv->getImagePv());
            $resultat->setCommune( $commune);
            $resultat->setEtat(0);
            $resultat->setCreatedAt(new \DateTimeImmutable);
            $resultat->setAutor($manager->getRepository(User::class)->findOneBy(["id"=>$this->getUser()->getId()]));
            $pv->setEtat(1);
            $manager->getManager()->persist($resultat);
            $manager->getManager()->persist($pv);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_manager");
        }*/
        
    }
}
