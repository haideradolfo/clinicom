<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SignupType;
use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/signin', name: 'signin')]
    public function signin(): Response
    {
        return $this->render('Login/signin.html.twig');
    }

    #[Route('/signup', name: 'signup')]
    public function signup(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $user = new User();
        $form = $this->createForm(SignupType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérification de la spécialité pour les médecins
            if ($user->getRole() === Role::Medecin && empty($user->getSpecialite())) {
                $this->addFlash('error', 'La spécialité est obligatoire pour les médecins');
                return $this->redirectToRoute('signup');
            }

            // Hachage du mot de passe
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('mdp')->getData()
            );
            $user->setMdp($hashedPassword);

            // Définition des champs supplémentaires
            $user->setVille($form->get('ville')->getData());
            $user->setAdresse($form->get('adresse')->getData());
            $user->setDateNaissance($form->get('date_naissance')->getData());

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('signin');
        }

        return $this->render('Login/signup.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/users', name: 'user_list')]
    public function list(EntityManagerInterface $em): Response
    {
        // Récupération de tous les utilisateurs
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}/edit', name: 'user_edit')]
    public function edit(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SignupType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement des changements dans la base de données
            $em->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès !');
            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/{id}/delete', name: 'user_delete')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        // Suppression de l'utilisateur de la base de données
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès !');
        return $this->redirectToRoute('user_list');
    }
}
