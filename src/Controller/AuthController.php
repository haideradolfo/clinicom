<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/signin', name: 'signin')]
    public function signin(): Response
    {
        return $this->render('Login/signin.html.twig');
    }

    #[Route('/signup', name: 'signup')]
    public function signup(): Response
    {
        return $this->render('Login/signup.html.twig');
    }
}
