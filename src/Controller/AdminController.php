<?php

namespace App\Controller;

use App\Form\CitoyenType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            
        ]);
    }

    #[Route('/admin/list-soumission', name: 'app_soumission')]
    public function soumissionView(): Response
    {
        return $this->render('admin/soumissionView.html.twig', [
            
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

    #[Route('/admin/resultat/verification', name: 'check')]
    public function verification(): Response
    {
        $form=$this->createForm(CitoyenType::class);
        return $this->render('admin/check.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}
