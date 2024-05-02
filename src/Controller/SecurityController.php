<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'security.login', methods: ['POST','GET'])]
    public function login(
        Request $request,
        AuthenticationUtils $authenticationUtils
    ): Response {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    #[Route('/logout', name: 'security.logout', methods: ['POST'])]
    public function logout()
    {
        // This method is intentionally left blank
        // Symfony's security system will intercept requests to this route and handle logout internally
    }
}
