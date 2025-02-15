<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('redirect_after_login'); // ✅ Redirection après connexion
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/redirect-after-login', name: 'redirect_after_login')]
    public function redirectAfterLogin(Security $security): RedirectResponse
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('admin_dashboard'); // ✅ Page pour l'admin
        } elseif (in_array('ROLE_MEDECIN', $user->getRoles())) {
            return $this->redirectToRoute('medecin_dashboard'); // ✅ Page pour le médecin
        } else {
            return $this->redirectToRoute('app_home'); // ✅ Page pour le patient
        }
    }

    #[Route('/test-role', name: 'test_role')]
    public function testRole(Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            return new Response('Aucun utilisateur connecté.');
        }

        return new Response('Rôles de l\'utilisateur : ' . implode(', ', $user->getRoles()));
    }
}
