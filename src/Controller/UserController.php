<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Form\ValidatorType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_user')]
    public function index(ManagerRegistry $manager): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $manager->getRepository(User::class)->findAll(),
        ]);
    }

    #[Route('/bloqued', name: 'app_bloqued')]
    public function bloqued(): Response
    {
        return $this->render('user/bloqued.html.twig', [
           
        ]);
    }
   
    #[Route('/admin/user/new', name:'add_user')]
    public function createUser(ManagerRegistry $manager,Request $request,UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();

        $form= $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash= $encoder->hashPassword($user,"123Ocel");
            $user->setUsername($form['username']->getData());
            $user->setNom($form['nom']->getData());
            $user->setPrenom($form['prenom']->getData());
            $user->setTelephone($form['telephone']->getData());
            $user->setEmail($form['telephone']->getData());
            $user->setRoles([$form['roles']->getData()]);
            $user->setPassword($hash);
            $user->setStatus(0);
            $user->setSexe($form['sexe']->getData());
            if($form["validator"]->getData()){
                $user->setValidateur($form["validator"]->getData()->getId());
            }
            $manager->getManager()->persist($user);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_user");
        }
        return $this->render('user/form.html.twig', [ 
            'form'=> $form->createView(),
        ]);
    }
    #[Route('/admin/{id}/add/validateur', name:'set_validator')]
    public function setValidator(?int $id,ManagerRegistry $manager, Request $request) : Response
    {
        $user = $manager->getRepository(User::class)->findOneBy(["id"=>$id]);
        $form = $this->createForm(ValidatorType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setValidateur($form['user']->getData()->getId());
            $manager->getManager()->persist($user);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_user");
        }
        return $this->render('user/validateur.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

    #[Route('/user/{id}/password/update', name:'create_password')]
    public function updateUserPassword(?int $id,ManagerRegistry $manager,UserPasswordHasherInterface $encoder, Request $request): Response
    {
        $error="";
        $user = $manager->getRepository(User::class)->findOneBy(["id"=>$id]);
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if($form["newPass"]->getData() != $form["confPass"]->getData()){
                $error="les mots de passe ne correspondent pas";
                return $this->redirectToRoute("create_password",["id"=>$user->getId()]);
            }
            $pass=$form["newPass"]->getData();
            $hash= $encoder->hashPassword($user,$pass);
            $user->setPassword($hash);
            $user->setStatus(1);
            $manager->getManager()->persist($user);
            $manager->getManager()->flush();
           // dd("done");
            if (in_array('ROLE_OPERATOR',$user->getRoles())) {
                return $this->redirectToRoute('app_manager');
            } else if (in_array('ROLE_SUPERVISOR', $user->getRoles())) {
                return $this->redirectToRoute('app_superviseur');
            }
            else if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('administration');
            }
            else if (in_array('ROLE_VIEWER', $user->getRoles())) {
                return $this->redirectToRoute('app_resultat');
            }
            //return $this->redirectToRoute("app_admin");
        }
        return $this->render('user/passwordForm.html.twig', [
            'form'=> $form->createView(),
            'error' =>$error,
        ]);
    }

    #[Route('admin/user/{id}/password/reset', name:'reset_password')]
    public function resetUserPassword(?int $id,ManagerRegistry $manager,UserPasswordHasherInterface $encoder): Response
    {
        $user = $manager->getRepository(User::class)->findOneBy(["id"=>$id]);
        if ($user != null) {
            $hash= $encoder->hashPassword($user,"123Ocel");
            $user->setPassword($hash);
            $user->setStatus(0);
            $manager->getManager()->persist($user);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_user");
        }
        return $this->render('user/passwordForm.html.twig', [

        ]);
    }

    #[Route('admin/user/{id}/bloquer', name:'bloquer_user')]
    public function bloquerUser(?int $id,ManagerRegistry $manager): Response
    {
        $user = $manager->getRepository(User::class)->findOneBy(["id"=>$id]);
        if ($user != null) {
            $user->setStatus(2);
            $manager->getManager()->persist($user);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_user");
        }
        return $this->render('user/passwordForm.html.twig', [

        ]);
    }
}
