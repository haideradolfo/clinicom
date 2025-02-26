<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profile', name: 'front_profile_', options: ['security' => "is_granted('ROLE_PATIENT') or is_granted('ROLE_MEDECIN')"])]
class FrontProfileController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        // Vérifier l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Vérification des rôles (au cas où)
        $this->denyAccessUnlessGranted('ROLE_PATIENT');
        $this->denyAccessUnlessGranted('ROLE_MEDECIN');

        // Création et gestion du formulaire
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du mot de passe uniquement si un nouveau est saisi
            $newPassword = $form->get('mdp')->getData();
            if (!empty($newPassword)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setMdp($hashedPassword);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('front_profile_index');
        }

        return $this->render('front_profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
