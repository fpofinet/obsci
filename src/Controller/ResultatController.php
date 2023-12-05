<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Departement;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResultatController extends AbstractController
{
    #[Route('/resultat', name: 'app_resultat')]
    public function index(ManagerRegistry $manager): Response
    {
        $province=$manager->getRepository(Province::class)->findAll();
        $provinces=array();
        $national=array();
        foreach($province as $p){
            $prov=array(); $votant=0;$sf=0; $vo=0; $vn=0; $sn=0;
            foreach($p->getDepartements() as $d){
                foreach($d->getCommunes() as $c){
                    foreach ($c->getResultats() as $r) {
                        if ($r->getEtat() == 0) {
                            $votant = $votant + $r->getVotant();
                            $sf = $sf + $r->getSuffrageExprime();
                            $sn = $sn + $r->getSuffrageNul();
                            $vo = $vo + $r->getVoteOui();
                            $vn = $vn + $r->getVoteNon();
                        }
                    }     
                }
            }
            $prov=["id"=>$p->getId(),"libelle"=>$p->getLibelle(),"votant"=>$votant,"suffrageExprime"=>$sf,"suffrageNul"=>$sn,"voteOui"=>$vo,"voteNon"=>$vn];
            $provinces[]=$prov; 
        }
        $v=0;$se=0;$bn=0;$o=0;$n=0;
        foreach($provinces as $p){
            $v= $v + $p["votant"];
            $se= $se + $p["suffrageExprime"];
            $bn= $bn + $p["suffrageNul"];
            $o= $o + $p["voteOui"];
            $n= $n + $p["voteNon"];
           
        }
        $national=["votant"=>$v,"suffrageExprime"=>$se,"suffrageNul"=>$bn,"voteOui"=>$o,"voteNon"=>$n];
        return $this->render('resultat/index.html.twig', [
            'province' => $provinces,
            'national' =>$national
        ]);
    }

    #[Route('/resultat/province/{id}', name: 'province')]
    public function detailsProvince(?int $id, ManagerRegistry $manager): Response
    {
        $depart=array();
        $depts = $manager->getRepository(Province::class)->findOneBy(['id' => $id]);
        foreach ($depts->getDepartements() as $p) {
            $dep = array();
            $votant = 0;
            $sf = 0;
            $vo = 0;
            $vn = 0;
            $sn = 0;
            $bvd = 0;
            $bv = 0;
            foreach ($p->getCommunes() as $c) {
                foreach ($c->getResultats() as $r) {
                    if ($r->getEtat() == 0) {
                        $votant = $votant + $r->getVotant();
                        $sf = $sf + $r->getSuffrageExprime();
                        $sn = $sn + $r->getSuffrageNul();
                        $vo = $vo + $r->getVoteOui();
                        $vn = $vn + $r->getVoteNon();
                    }
                }   
            }
            $dep = ["id" => $p->getId(), "libelle" => $p->getLibelle(), "votant" => $votant, "suffrageExprime" => $sf, "suffrageNul" => $sn, "voteOui" => $vo, "voteNon" => $vn, "bvd" => $bvd, "bv" => $bv];
            $depart[] = $dep;
        }
        return $this->render('resultat/detailsProvince.html.twig', [
           'depts'=> $depart,
           'label' =>$depts->getLibelle(),
        ]);
    }

    #[Route('/resultat/departement/{id}', name: 'departement')]
    public function detailsDepartement(?int $id,ManagerRegistry $manager): Response
    {
        $comm=array();
        $comms = $manager->getRepository(Departement::class)->findOneBy(['id' => $id]);
        foreach ($comms->getCommunes() as $c) {
            $dep = array();
            $votant = 0;
            $sf = 0;
            $vo = 0;
            $vn = 0;
            $sn = 0;
            foreach ($c->getResultats() as $r) {
                if ($r->getEtat() == 5) {
                    $votant = $votant + $r->getVotant();
                    $sf = $sf + $r->getSuffrageExprime();
                    $sn = $sn + $r->getSuffrageNul();
                    $vo = $vo + $r->getVoteOui();
                    $vn = $vn + $r->getVoteNon();
                }
            }
            $dep = ["id" => $c->getId(), "libelle" => $c->getLibelle(), "votant" => $votant, "suffrageExprime" => $sf, "suffrageNul" => $sn, "voteOui" => $vo, "voteNon" => $vn];
            $comm[] = $dep;
        }
        return $this->render('resultat/detailsDepartement.html.twig', [
            'com'=>$comm,
            'label'=> $comms->getLibelle(),
        ]);
    }

    #[Route('/resultat/commune/{id}', name: 'commune')]
    public function detailsCommune(?int $id,ManagerRegistry $manager): Response
    {
        $bvs = $manager->getRepository(Commune::class)->findOneBy(['id' => $id]);
       /* $bureauV=array();

        $dep = array();
        $votant = 0;
        $sf = 0;
        $vo = 0;
        $vn = 0;
        $sn = 0;
        foreach ($bvs->getResultats() as $r) {
            if ($r->getEtat() == 5) {
                $votant = $votant + $r->getVotant();
                $sf = $sf + $r->getSuffrageExprime();
                $sn = $sn + $r->getSuffrageNul();
                $vo = $vo + $r->getVoteOui();
                $vn = $vn + $r->getVoteNon();
                $dep = ["id" => $bvs->getId(), "libelle" => $r->getCodeBureau(), "votant" => $votant, "suffrageExprime" => $sf, "suffrageNul" => $sn, "voteOui" => $vo, "voteNon" => $vn ,"bv" =>count($bvs->getBureauVote())];
            }
            $bureauV[] = $dep;
        }*/
            
            
        
        return $this->render('resultat/detailsCommune.html.twig', [
            'datas' => $bvs->getResultats(),
            'label'=> $bvs->getLibelle(),
        ]);
    }

    #[Route('/conso', name:'conso')]
    public function consolider(ManagerRegistry $manager){
     //   $t= $manager->getRepository(Resultat::class)->findBy(['bureauVote'=> $manager->getRepository(BureauVote::class)->findOneBy(['code'=> 'PAL001'])]);
       // $tab=$this->conso($t);

       /// $tableauUnique = array_unique($tab,SORT_REGULAR);
      //  dd($tableauUnique);
    }

    private function conso($t)
    {
        $output=array();
        for($i= 0; $i<count($t)-1; $i++ ){
           $eg=array();
            for( $j= 0; $j<count($t); $j++ ){
                if(Utils::comparerResultat($t[$i],$t[$j])){
                    $eg[]=$t[$j];
                } else{
                }
            }
            $output[]=$eg;
        }
        return $output;
    }
}
