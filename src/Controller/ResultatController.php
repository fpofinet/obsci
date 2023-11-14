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

    #[Route('/resultat2', name: 'index2')]
    public function index2(): Response
    {
        return $this->render('resultat/index2.html.twig', [
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

    #[Route('/resultat/province2/', name: 'province2')]
    public function detailsProvince2(): Response
    {
        return $this->render('resultat/detailsProvince2.html.twig', [
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

    #[Route('/resultat/province2/departement2/', name: 'departement2')]
    public function detailsDepartement2(): Response
    {
        return $this->render('resultat/detailsDepartement2.html.twig', [
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
    #[Route('/resultat/province2/departement2/commune2/', name: 'commune2')]
    public function detailsCommune2(): Response
    {
        return $this->render('resultat/detailsCommune2.html.twig', [
            'controller_name' => 'ResultatController',
        ]);
    }

    #[Route('/resultat/province2/departement2/commune2/bureauVote/11', name: 'bureauVote')]
    public function detailsBurauVote(): Response
    {
        return $this->render('resultat/detailsBureauVote.html.twig', [
            'controller_name' => 'ResultatController',
        ]);
    }
}
