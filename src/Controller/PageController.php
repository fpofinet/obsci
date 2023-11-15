<?php

namespace App\Controller;

use App\Form\CitoyenType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            
        ]);
    }

    #[Route('/espace-citoyen', name: 'app_espaceCi')]
    public function espaceCitoyen(): Response
    {
        $form=$this->createForm(CitoyenType::class);
        return $this->render('page/espaceCitoyen.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/apropos', name: 'app_apropos')]
    public function apropos(): Response
    {
        return $this->render('page/apropos.html.twig', [
            
        ]);
    }
}
