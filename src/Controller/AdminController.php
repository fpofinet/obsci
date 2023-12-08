<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Commune;
use App\Entity\Province;
use App\Entity\Resultat;
use App\Entity\Departement;
use App\Entity\ResultatKobo;
use App\Form\AllocationType;
use App\Form\UploadFileType;
use App\Entity\ResultatOperateur;
use App\Controller\ExcelConnector;
use App\Entity\ResultatSuperviseur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    //index operateur
    #[Route('/operateur', name: 'app_manager')]
    public function index(ManagerRegistry $manager): Response
    {
         
        /* @SuppressWarnings(P1013)*/
        $datas=$manager->getRepository(ResultatKobo::class)->findBy(["allowedTo"=>$this->getUser()->getId(),"etat"=>0]);
        return $this->render('admin/manager.html.twig', [
            'datas'=>$datas,
        ]);
    }

    //verification
   #[Route('/operateur/pv/{id}', name: 'check')]
    public function verification(?int $id,ManagerRegistry $manager,Request $request): Response
    {
       // $kobo = new KoboConnector("004998ce52dc528dd4d1c5045ea702816aa9bb68");
        $pv= $manager->getRepository(ResultatKobo::class)->findOneBy(["id"=> $id]);
       // $img=$kobo->downloadImg($pv->getImagePv());
        //if($img !=null){
           // $pv->setImagePv($img);
        //}
        
        $manager->getManager()->persist($pv);
        $manager->getManager()->flush();
        return $this->render('admin/check.html.twig', [
           'pv'=> $pv,
        ]);
    }

    //saisie de données
    #[Route('/operateur/saisie', name:'saisie')]
    public function saisieOperateur(Request $request, ManagerRegistry $manager)
    {
        if($request->isMethod("POST")){
            $pv=$manager->getRepository(ResultatKobo::class)->findOneBy(["id"=>$request->request->all()['idPv']]);
            $commune=$manager->getRepository(Commune::class)->findOneBy(["id"=>$request->request->all()['commune']]);
            $resultat = new ResultatOperateur();
            $resultat->setValidateur($this->getUser()->getValidateur());
            $resultat->setAgentSaisie($request->request->all()['agent']);
            $resultat->setCodeBureau($commune->getCode()."_".$request->request->all()['codeBureau']);
            $resultat->setVotant($request->request->all()['votant']);
            $resultat->setSuffrageExprime($request->request->all()['suffrageExprime']);
            $resultat->setSuffrageNul($request->request->all()['suffrageNul']);
            $resultat->setVoteOui($request->request->all()['voteOui']);
            $resultat->setVoteNon($request->request->all()['voteNon']);
            $resultat->setCode($pv->getCodeKobo());
            $resultat->setImagePv($pv->getImagePv());
            if($pv->getSubmitter()== null){
                $resultat->setSubmitter("anonyme");
            } else {
                $resultat->setSubmitter($pv->getSubmitter());
            }
            
            $resultat->setSubmittedOn(DateTimeImmutable::createFromMutable($pv->getDateSubmit()));
            $resultat->setCommune( $commune);
            $resultat->setEtat(0);
            $resultat->setCreatedAt(new DateTimeImmutable);
            $resultat->setAutor($manager->getRepository(User::class)->findOneBy(["id"=>$this->getUser()->getId()]));
            $pv->setEtat(1);
            $manager->getManager()->persist($resultat);
            $manager->getManager()->persist($pv);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_manager");
        } 
    }

    //synchronisation kobo
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
                $temp->setImagePv($kobo->downloadImg($d["_attachments"][0]["download_small_url"]));
                $temp->setDateSubmit(new \DateTime($d["_submission_time"]));
                $temp->setAllowedOn(new DateTimeImmutable);
                $temp->setEtat(0);
                $manager->getManager()->persist($temp);
                $manager->getManager()->flush();
            }
        }
        $this->allocation($manager);
        return $this->redirectToRoute("administration");
    }

    // allocation
   // #[Route('/alloc', name:'alloc')]
    private function allocation(ManagerRegistry $manager)
    {
        $data=$manager->getRepository(ResultatKobo::class)->findBy(["etat"=>0]);
        $user=$manager->getRepository(User::class)->findAll();
        $operator=array();
        foreach($user as $r){
            if(in_array('ROLE_OPERATOR',$r->getRoles())){
                $operator[]=$r;
            }
        }
        $byUser=count($data)/count($operator);
        $index=0;
        
        for($i=0;$i<count($operator);$i++){
            $fin=$index+$byUser;
            for($j=$index;$j<$fin;$j++){
                $data[$j]->setAllowedTo($operator[$i]->getId());
                $data[$j]->setAllowedOn(new DateTimeImmutable);
                $manager->getManager()->persist($data[$j]);
                $manager->getManager()->flush();
            }
            $index = $fin;
        }
    }


    // index superviseur
    #[Route('/superviseur', name:'app_superviseur')]
    public function index_superviseur(ManagerRegistry $manager): Response{
        return $this->render('admin/superviseur.html.twig', [
           'bvs'=> $manager->getRepository(ResultatOperateur::class)->findBy(["etat"=>0,"validateur"=>$this->getUser()->getId()]),
        ]);
    }

    //verfication saisie
    #[Route('/superviseur/pv/{id}', name:'detailsPV')]
    public function verifier(?int $id,ManagerRegistry $manager):Response{
        return $this->render('admin/detailsPv.html.twig',[
           'pv' => $manager->getRepository(ResultatOperateur::class)->findOneBy(["id"=>$id,"etat"=>0]),
        ]);
    }

    //validation saisie
    #[Route('/superviseur/validation', name:'supValider')]
    public function handleData(Request $request,ManagerRegistry $manager) :Response
    {
        if($request->isMethod("POST")){
            $pv=$manager->getRepository(ResultatOperateur::class)->findOneBy(["id"=>$request->request->all()['idPv']]);
            $commune=$manager->getRepository(Commune::class)->findOneBy(["id"=>$request->request->all()['commune']]);
          //  dd($commune);
            $resultat = new ResultatSuperviseur();
            $resultat->setCodeBureau($request->request->all()['codeBureau']);
            $resultat->setVotant($request->request->all()['votant']);
            $resultat->setSuffrageExprime($request->request->all()['suffrageExprime']);
            $resultat->setSuffrageNul($request->request->all()['suffrageNul']);
            $resultat->setVoteOui($request->request->all()['voteOui']);
            $resultat->setVoteNon($request->request->all()['voteNon']);
            $resultat->setAgentValidation($request->request->all()['agent']);
            $resultat->setAgentSaisie($pv->getAgentSaisie());
            $resultat->setImagePv($pv->getImagePv());
            $resultat->setCode($pv->getCode());
            $resultat->setSubmittedOn($pv->getSubmittedOn());
            $resultat->setSubmitter($pv->getSubmitter());     
            $resultat->setAutor($pv->getAutor());
            $resultat->setCreatedAt($pv->getCreatedAt());
            $resultat->setValidator($manager->getRepository(User::class)->findOneBy(["id"=>$this->getUser()->getId()]));
            $resultat->setValidedOn(new DateTimeImmutable);
            $resultat->setCommune($commune);
            $resultat->setEtat(0);
            $pv->setEtat(1);
            $manager->getManager()->persist($resultat);
            $manager->getManager()->persist($pv);
            $manager->getManager()->flush();

            $existant=$manager->getRepository(Resultat::class)->findOneBy(["codeBureau"=>$pv->getCodeBureau(),"commune"=>$commune]);
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
                $resdef->setAgentValidation($request->request->all()['agent']);
                $resdef->setImagePv($pv->getImagePv());
                $resdef->setCommune($commune);
                $resdef->setEtat(0);
                $resdef->setCreatedAt($pv->getCreatedAt());
                $resdef->setAutor($pv->getAutor());
                $resdef->setValidator($resultat->getValidator());
                $resdef->setValidedOn($resultat->getValidedOn());
                $resdef->setAgentSaisie($pv->getAgentSaisie());
                $resdef->setSubmitter($pv->getSubmitter());
                $resdef->setSubmittedOn($pv->getSubmittedOn());
                $resultat->setEtat(2);
                $manager->getManager()->persist($resultat);
                $manager->getManager()->persist($resdef);
                $manager->getManager()->flush();
                return $this->redirectToRoute("app_superviseur");
            }
        }
        return $this->redirectToRoute("app_superviseur");
    }

    //rejet de la saisie
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


    //gestion des conflits 
    #[Route('/superviseur/{code}/{com}/consolidation', name:'checkSup')]
    public function consol(?string $code,?int $com,ManagerRegistry $manager):Response
    {
        $commune=$manager->getRepository(Commune::class)->findOneBy(["id"=>$com]);
        $actuel=$manager->getRepository(Resultat::class)->findOneBy(["codeBureau"=>$code,"commune"=>$commune]);
        //dd($commune);
        $new=$manager->getRepository(ResultatSuperviseur::class)->findOneBy(["codeBureau"=>$code,"etat"=>0,"commune"=>$commune]);
        $other=$manager->getRepository(ResultatSuperviseur::class)->findBy(["codeBureau"=>$code,"etat"=>1,"commune"=>$commune]);
        return $this->render('admin/checkSup.html.twig',[
            'actuel'=>$actuel,
            'new'=>$new,
            'other'=>$other,
         ]);
    }

    //conservation du resultat
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

    //remplacement des resultats
    #[Route('/superviseur/{id}/changer', name:'changerResultat')]
    public function changerResultat(?int $id,ManagerRegistry $manager):Response
    {
        $new=$manager->getRepository(ResultatSuperviseur::class)->findOneBy(["id"=>$id]);
        $actuel=$manager->getRepository(Resultat::class)->findOneBy(["codeBureau"=>$new->getCodeBureau(),"commune"=>$new->getCommune()]);
        if($actuel){
            $back = new ResultatSuperviseur();
            $back->setCode($actuel->getCode());
            $back->setImagePv($actuel->getImagePv());
            $back->setAgentSaisie($actuel->getAgentSaisie());
            $back->setAutor($actuel->getAutor());
            $back->setCreatedAt($actuel->getCreatedAt());
            $back->setAgentValidation($actuel->getAgentValidation());
            $back->setValidator($actuel->getValidator());
            $back->setValidedOn($actuel->getValidedOn());
            $back->setSubmitter($actuel->getSubmitter());
            $back->setSubmittedOn($actuel->getSubmittedOn());
            $back->setCodeBureau($actuel->getCodeBureau());
            $back->setSuffrageExprime($actuel->getSuffrageExprime());
            $back->setSuffrageNul($actuel->getSuffrageNul());
            $back->setVotant($actuel->getVotant());
            $back->setVoteNon($actuel->getVoteNon());
            $back->setVoteOui($actuel->getVoteOui());
            $back->setEtat(1);
            $back->setCommune($actuel->getCommune());

            $actuel->setSuffrageExprime($new->getSuffrageExprime());
            $actuel->setSuffrageNul($new->getSuffrageNul());
            $actuel->setVotant($new->getVotant());
            $actuel->setVoteOui($new->getVoteOui());
            $actuel->setVoteNon($new->getVoteNon());
            $actuel->setImagePv($new->getImagePv());
            $new->setEtat(2);
            $manager->getManager()->persist($actuel);
            $manager->getManager()->persist($back);
            $manager->getManager()->flush();
           
            
            return $this->redirectToRoute("app_superviseur");
        }
        return $this->redirectToRoute("app_superviseur");
    }

    // index administration
    #[Route('/admin', name:'administration')]
    public function admin(ManagerRegistry $manager ): Response
    {
        $user=$manager->getRepository(User::class)->findAll();
        $output=array();

        foreach($user as $u){
            if(in_array("ROLE_OPERATOR",$u->getRoles())){
                $nt=count($manager->getRepository(ResultatKobo::class)->findBy(["allowedTo"=>$u->getId()]));
                $tt=count($manager->getRepository(ResultatKobo::class)->findBy(["allowedTo"=>$u->getId(),"etat"=>1]));
                $output[]=["id"=>$u->getId(),"nom"=>$u->getNom(),"prenom"=>$u->getPrenom(),"username"=>$u->getUsername(),"role"=>$u->getRoles()[0],"tel"=>$u->getTelephone(),"total"=>$nt,"traiter"=>$tt];
              
            } else if(in_array("ROLE_SUPERVISOR",$u->getRoles())){
                $nt=count($manager->getRepository(ResultatOperateur::class)->findBy(["validateur"=>$u->getId()]));
                $tt=count($manager->getRepository(ResultatOperateur::class)->findBy(["validateur"=>$u->getId(),"etat"=>1]));
                $output[]=["id"=>$u->getId(),"nom"=>$u->getNom(),"prenom"=>$u->getPrenom(),"username"=>$u->getUsername(),"role"=>$u->getRoles()[0],"tel"=>$u->getTelephone(),"total"=>$nt,"traiter"=>$tt];
            }
            
        }
        return $this->render('admin/admin.html.twig',[
            "users" =>$output,
        ]);
    }

    #[Route('/admin/user/{id}/traitees', name:'details_traiter')]
    public  function detailsTraiter($id,ManagerRegistry $manager): Response
    {
        $user=$manager->getRepository(User::class)->findOneBy(["id"=>$id]);
        $datas=$manager->getRepository(ResultatKobo::class)->findBy(["allowedTo"=>$user->getId(),"etat"=>1]);
        return $this->render('admin/detailsTraiter.html.twig',[
            'datas' =>$datas,
            'user' =>$user,
        ]);
    }

    #[Route('/admin/user/{id}/non-traitees', name:'details_nontraiter')]
    public  function detailsNonTraiter($id,ManagerRegistry $manager): Response
    {
        $user=$manager->getRepository(User::class)->findOneBy(["id"=>$id]);
        $k="";
        if(in_array("ROLE_OPERATOR",$user->getRoles())){
            $datas=$manager->getRepository(ResultatKobo::class)->findBy(["allowedTo"=>$user->getId(),"etat"=>0]);
            $k="op";
        } else if(in_array("ROLE_SUPERVISOR",$user->getRoles())){
            $datas=$manager->getRepository(ResultatOperateur::class)->findBy(["validateur"=>$user->getId(),"etat"=>0]);
            $k="val";
        }
       
        return $this->render('admin/detailsNonTraiter.html.twig',[
            'datas' =>$datas,
            'user' =>$user,
            'val' =>$k,
        ]);
    }

    #[Route('/admin/user/{id}/reallocation', name:'realloc')]
    public  function reallocation($id,ManagerRegistry $manager,Request $request): Response
    {
        $user=$manager->getRepository(User::class)->findOneBy(["id"=>$id]);
        $datas=$manager->getRepository(ResultatKobo::class)->findBy(["allowedTo"=>$user->getId(),"etat"=>0]);
        $datas2=$manager->getRepository(ResultatOperateur::class)->findBy(["validateur"=>$user->getId(),"etat"=>0]);
        $form=$this->createForm(AllocationType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            if(in_array("ROLE_OPERATOR",$user->getRoles())){
                foreach($datas as $d){
                    $d->setAllowedTo($form["user"]->getData()->getId());
                    $manager->getManager()->persist($d);
                    $manager->getManager()->flush();
                }
            } else if(in_array("ROLE_SUPERVISOR",$user->getRoles())){
                foreach($datas2 as $d){
                    $d->setValidateur($form["user"]->getData()->getId());
                    $manager->getManager()->persist($d);
                    $manager->getManager()->flush();
                }
            }
            return $this->redirectToRoute("administration");
        }
        return $this->render('admin/formAllocation.html.twig',[
            'form' =>$form->createView(),
            'user' =>$user,
        ]);
    }

    /* liens ajax */


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
   

   
    // importation de données excel
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
    public function test(Request $request, ManagerRegistry $manager,UserPasswordHasherInterface $encoder)
    {
        $user = new User();
        $hash= $encoder->hashPassword($user,"123Ocel");
            $user->setUsername("fika");
            $user->setRoles(["ROLE_ADMIN"]);
            $user->setPassword($hash);
            $user->setStatus(0);
            $user->setSexe('M');
            $manager->getManager()->persist($user);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_login");
    }
}
