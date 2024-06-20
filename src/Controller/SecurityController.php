<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


/**
 * SecurityController
 *
 * This controller handles user authentication, including login and logout actions.
 */
class SecurityController extends AbstractController
{

    /**
     * Login action.
     *
     * This method handles both POST and GET requests to the login route ('/login').
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    #[Route('/login', name: 'app_login', methods: ['POST','GET'])]
    public function login(
        AuthenticationUtils $authenticationUtils
    ): Response {
        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);
    }

    /**
     * Logout
     *
     * This method handles the logout action.
     * 
     * @return void
     * 
     * @codeCoverageIgnore
     */
    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
        // This method is intentionally left blank
        // Symfony's security system will intercept requests to this route and handle logout internally
    }
}
