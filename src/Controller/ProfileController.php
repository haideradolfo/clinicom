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

#[Route('/admin/profile', name: 'admin_profile')]
#[IsGranted('ROLE_ADMIN')] // Empêche les autres rôles d'accéder
class ProfileController extends AbstractController
{
    #[Route('/', name: '_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Créer et traiter le formulaire
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe saisi
            $newPassword = $form->get('mdp')->getData();

            // Si un mot de passe a été fourni (non vide), le hasher et le mettre à jour
            if (!empty($newPassword)) {
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setMdp($hashedPassword);  // Mise à jour du mot de passe
                $this->addFlash('success', 'Mot de passe mis à jour avec succès.');
            } else {
                // Si aucun mot de passe n'est fourni, on laisse l'ancien mot de passe inchangé
                $this->addFlash('info', 'Aucun changement de mot de passe.');
            }

            // Mettre à jour l'utilisateur dans la base de données
            $entityManager->flush();

            // Message flash pour indiquer que le profil a été mis à jour
            $this->addFlash('success', 'Votre profil a été mis à jour.');

            // Rediriger vers la page du profil
            return $this->redirectToRoute('admin_profile_index');
        }

        // Rendre la vue du profil avec le formulaire
        return $this->render('profile/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
