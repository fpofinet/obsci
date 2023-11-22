<?php

namespace App\Controller;

use App\Entity\Resultat;
use App\Form\CitoyenType;
use App\Entity\BureauVote;
use App\Controller\MockData;
use App\Entity\TempResultat;
use Doctrine\Persistence\ManagerRegistry;
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

    #[Route('/admin/resultats', name:'resultats')]
    public function resultats(): Response{
        return $this->render('admin/resultat.html.twig', []);
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
