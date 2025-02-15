<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Modification ici pour les annotations
use Doctrine\ORM\EntityManagerInterface; // Ajout de l'import pour EntityManagerInterface
use App\Entity\User; // Ajout de l'import pour l'entité User

final class AdminHomeController extends AbstractController
{
    #[Route('/admin/home', name: 'app_admin_home')]
    public function index(): Response
    {
        return $this->render('admin_home/index.html.twig', [
            'controller_name' => 'AdminHomeController',
        ]);
    }

    #[Route('/medecin/dashboard', name: 'medecin_dashboard')]
    public function medecinDashboard(): Response
    {
        return $this->render('admin_home/medecin/dashboard.html.twig', [
            'controller_name' => 'MedecinDashboardController',
        ]);
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        // Récupérer les utilisateurs depuis la base de données
        $users = $entityManager->getRepository(User::class)->findAll();
    
        // Passer les utilisateurs à la vue Twig
        return $this->render('admin_home/admin/dashboard.html.twig', [
            'users' => $users,
        ]);
    }
}
