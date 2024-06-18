<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


/**
 * DefaultController
 *
 * This controller handles the homepage of the application.
 */
class DefaultController extends AbstractController
{

    /**
     * Homepage action.
     *
     * This method handles the GET request to the homepage route ('/'). 
     *
     * @return Response
     */
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function index()
    {
        // Get the currently logged in user (if any)
        $user = $this->getUser();

        // Render the template with the 'app.user' variable
        return $this->render('default/index.html.twig', [
            'user' => $user,
        ]);
    }
}
