<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Resultat;
use App\Form\CitoyenType;
use App\Form\CommuneType;
use App\Entity\BureauVote;
use App\Form\ProvinceType;
use App\Entity\Departement;
use App\Controller\MockData;
use App\Entity\TempResultat;
use App\Form\BureauVoteType;
use App\Form\DepartementType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ManagerRegistry $manager): Response
    {
        $datas=$manager->getRepository(TempResultat::class)->findBy(["etat"=>0]);
        return $this->render('admin/index.html.twig', [
            'datas'=>$datas,
        ]);
    }

   

    #[Route('/admin/resultats/province', name:'resultat_province')]
    public function resultatProvince(): Response{
        return $this->render('admin/tableProvince.html.twig', []);
    }

    #[Route('/admin/resultats/province/departement', name:'resultat_dept')]
    public function resultatDept(): Response{
        return $this->render('admin/tableDepartement.html.twig', []);
    }

    #[Route('/admin/resultats/province/departement/commune', name:'resultat_commune')]
    public function resultatCommune(): Response{
        return $this->render('admin/tableCommune.html.twig', []);
    }

    #[Route('/admin/resultat/ajouter/', name: 'communeList')]
    public function communeList(): Response
    {
        return $this->render('admin/communeList.html.twig', [
            
        ]);
    }

    #[Route('/admin/resultat/ajouter/form', name: 'add_resultat')]
    public function addResult(): Response
    {
        $form=$this->createForm(CitoyenType::class);
        return $this->render('admin/formAddResult.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    //verification

    #[Route('/admin/resultat/{id}/verification', name: 'check')]
    public function verification(?int $id,ManagerRegistry $manager): Response
    { 
        $pv= $manager->getRepository(TempResultat::class)->findOneBy(["id"=>$id]);
        $others=$manager->getRepository(Resultat::class)->findBy(["bureauVote"=>$manager->getRepository(BureauVote::class)->findOneBy(["code"=>$pv->getBureauVote()])]);
        if($pv!=null){
            $pv->setEtat(1);
            $manager->getManager()->persist($pv);
            $manager->getManager()->flush();
        }
        return $this->render('admin/check.html.twig', [
           "pv"=> $pv,
           "others"=> $others,
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
            return $this->redirectToRoute("app_admin");
        }

        return $this->render('admin/check.html.twig', []);
    }


    //consolidation

    #[Route('/admin/consolidation', name:'consolidation')]
    public function resultats(ManagerRegistry $manager): Response{
        $bvs=$manager->getRepository(BureauVote::class)->findAll();
        $identiques =0;
        $discordantes = 0;
        $output=array();

        foreach($bvs as $b) {
           foreach($b->getResultat() as $r){
            //if($r->getSuffrageExprime()
           }

        }


        return $this->render('admin/consolider.html.twig', []);
    }

    #[Route('/admin/consolidation/{id}/consolider', name:'consolider')]
    public function consolider(ManagerRegistry $manager):Response{


        return $this->render('');
    }

    //administration

    #[Route('/admin/administration', name:'administration')]
    public function admin(): Response
    {
        return $this->render('admin/admin.html.twig',[

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


    //faker 
    #[Route('/admin/faker', name:'faker')]
    public function verificationList(ManagerRegistry $manager): Response
    {
        /*$myMock= MockData::$data1;

        foreach($myMock as $m){
            $temp=new TempResultat();
            $temp->setProvince($m['province']);
            $temp->setDepartement($m['departement']);
            $temp->setCommune($m['commune']);
            $temp->setBureauVote($m['bureauVote']);
            $temp->setNombreVotant($m['votant']);
            $temp->setSuffrageExprime($m['suffrageExp']);
            $temp->setBulletinNuls($m['bulletinNuls']);
            $temp->setVoteOui($m['voteOui']);
            $temp->setVoteNon($m['voteNon']);
            $temp->setDate(new \DateTime("10-10-2023"));
            $temp->setIdSubmitter($m['idSubmitter']);
            $temp->setCodeKobo($m['codePV']);
            $temp->setEtat(0);

            $manager->getManager()->persist($temp);
            $manager->getManager()->flush();
            
        }
        dd("end");*/
        return $this->render('', [  ]);
    }
}
