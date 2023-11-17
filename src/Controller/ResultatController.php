<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultatController extends AbstractController
{
    #[Route('/resultat', name: 'app_resultat')]
    public function index(): Response
    {
        return $this->render('resultat/index.html.twig', [
            'controller_name' => 'ResultatController',
        ]);
    }

    #[Route('/resultat/province/', name: 'province')]
    public function detailsProvince(): Response
    {
        return $this->render('resultat/detailsProvince.html.twig', [
            'controller_name' => 'ResultatController',
        ]);
    }

    #[Route('/resultat/province/departement/', name: 'departement')]
    public function detailsDepartement(): Response
    {
        return $this->render('resultat/detailsDepartement.html.twig', [
            'controller_name' => 'ResultatController',
        ]);
    }

    #[Route('/resultat/province/departement/commune/', name: 'commune')]
    public function detailsCommune(): Response
    {
        return $this->render('resultat/detailsCommune.html.twig', [
            'controller_name' => 'ResultatController',
        ]);
    }
}
