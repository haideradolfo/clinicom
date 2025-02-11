<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackofficeController extends AbstractController
{
    #[Route('/backoffice', name: 'admin_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('backoffice/index.html.twig', [
            'title' => 'Tableau de bord'
        ]);
    }

    #[Route('/backofficeuser', name: 'admin_users')]
    public function users(): Response
    {
        return $this->render('backoffice/index.html.twig', [
            'title' => 'Gestion des utilisateurs'
        ]);
    }
}