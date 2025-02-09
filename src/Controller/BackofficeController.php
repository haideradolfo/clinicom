<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackofficeController extends AbstractController
{
    #[Route('/backoffice', name: 'backoffice_index')]
    public function index(): Response
    {
        return $this->render('backoffice/index.html.twig');
    }
}
