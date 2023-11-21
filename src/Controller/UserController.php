<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
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
   
    #[Route('/admin/user/new', name:'add_user')]
    public function createUser(ManagerRegistry $manager,Request $request,UserPasswordHasherInterface $encoder): Response
    {
        $user = new User();

        $form= $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          // dd($form['roles']->getData());
            $pass=$this->generatePassword();
            $hash= $encoder->hashPassword($user,$pass);
            $user->setUsername($form['username']->getData());
            $user->setRoles([$form['roles']->getData()]);
            $user->setPassword($hash);

            $manager->getManager()->persist($user);
            $manager->getManager()->flush();
            return $this->render('user/info.html.twig', [
                'nom'=> $form['username']->getData(),
                'mp'=>$pass,
            ]);;
        }
        return $this->render('user/form.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

    #[Route('admin/user/{id}/password/update', name:'update_password')]
    public function updateUserPassword(?int $id,ManagerRegistry $manager,UserPasswordHasherInterface $encoder, Request $request): Response
    {
        $user = $manager->getRepository(User::class)->findOneBy(["id"=>$id]);
        $form = $this->createForm(UserPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pass=$form["newPass"]->getData();
            $hash= $encoder->hashPassword($user,$pass);
            $user->setPassword($hash);
            $manager->getManager()->persist($user);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_admin");
        }
        return $this->render('user/passwordForm.html.twig', [

        ]);
    }

    #[Route('admin/user/{id}/password/reset', name:'reset_password')]
    public function resetUserPassword(?int $id,ManagerRegistry $manager,UserPasswordHasherInterface $encoder): Response
    {
        $user = $manager->getRepository(User::class)->findOneBy(["id"=>$id]);
        if ($user != null) {
            $hash= $encoder->hashPassword($user,"123456");
            $user->setPassword($hash);
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
            $user->setStatus(0);
            $manager->getManager()->persist($user);
            $manager->getManager()->flush();
            return $this->redirectToRoute("app_user");
        }
        return $this->render('user/passwordForm.html.twig', [

        ]);
    }

    private function generatePassword($length = 8, $characters = '0123456789abcdefghijklmnopqrstuvwxyz')
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
