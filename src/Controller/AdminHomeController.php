<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\User;
use App\Form\ProfileType;

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
public function dashboard(Request $request, UserRepository $userRepository): Response
    {
        $search = $request->query->get('q');
        $sort = $request->query->get('sort', 'id'); // Valeur par défaut 'id'
        $direction = $request->query->get('direction', 'asc'); // Valeur par défaut 'asc'
    
        $users = $userRepository->findBySearchAndSort($search, $sort, $direction);
    
        // Vérification si la requête est AJAX
        if ($request->isXmlHttpRequest()) {
            return $this->render('admin_home/admin/_user_table.html.twig', [
                'users' => $users
            ]);
        }
    
        return $this->render('admin_home/admin/dashboard.html.twig', [
            'users' => $users,
            'search' => $search
        ]);
    }
    
}
