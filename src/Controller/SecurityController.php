<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {


        if ($this->getUser()) {
            if($this->getUser()->getStatus() == 0){
                return $this->redirectToRoute('app_logout');
            }
            if($this->getUser()->getStatus() == 1){
                if (in_array('ROLE_MANAGER',$this->getUser()->getRoles())) {
                    return $this->redirectToRoute('app_manager');
                } else if (in_array('ROLE_SUPERVISOR', $this->getUser()->getRoles())) {
                    return $this->redirectToRoute('app_superviseur');
                }
                else if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                    return $this->redirectToRoute('administration');
                }
                else if (in_array('ROLE_VIEWER', $this->getUser()->getRoles())) {
                    return $this->redirectToRoute('app_resultat');
                } else{
                    return $this->redirectToRoute('app_logout');
                }
            }
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
