<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        // Get the currently logged in user (if any)
        $user = $this->getUser();

        // Render the template with the 'app.user' variable
        return $this->render('default/index.html.twig', [
            'user' => $user,
        ]);
    }
}
