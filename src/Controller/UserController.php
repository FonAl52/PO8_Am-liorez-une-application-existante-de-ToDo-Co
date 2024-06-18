<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


/**
 * UserController
 *
 * This controller handles user-related actions, including listing, creating, and editing users.
 */
class UserController extends AbstractController
{

    /**
     * List users.
     *
     * Handles both GET and POST requests to the '/users' route.
     * 
     * @param UserRepository $userRepository
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return Response
     */
    #[Route('/users', name: 'user_list', methods: ['GET', 'POST'])]
    public function listAction(
        UserRepository $userRepository,
        AuthorizationCheckerInterface $authorizationChecker
    ): Response {
        if (!$authorizationChecker->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/list.html.twig', ['users' => $userRepository->findAll()]);
    }
    
    /**
     * Create a new user.
     *
     * Handles both GET and POST requests to the '/users/create' route.
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return Response The response object containing the rendered template
     */
    #[Route('/users/create', name: 'user_create', methods: ['GET','POST'])]
    public function createAction(
        Request $request, 
        EntityManagerInterface $em,
        AuthorizationCheckerInterface $authorizationChecker
    ): Response {
        if (!$authorizationChecker->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('homepage');
        }

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
            $user = $form->getData();
            
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('homepage');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Ce nom d\'utilisateur existe déjà.');
                
                return $this->redirectToRoute('user_create');
            }
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit a user.
     *
     * Handles both GET and POST requests to the '/users/{id}/edit' route.
     * 
     * @param User $user
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param AuthorizationCheckerInterface $authChecker
     *
     * @return Response
     */
    #[Route('/users/{id}/edit', name: 'user_edit', methods: ['GET','POST'])]
    public function editAction(
        User $user, 
        Request $request, 
        EntityManagerInterface $em,
        AuthorizationCheckerInterface $authChecker
    ): Response {
        if (!$authChecker->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user = $form->getData();
                
                $em->persist($user);
                $em->flush();

                $this->addFlash('success', "L'utilisateur a bien été modifié");
    
                return $this->redirectToRoute('user_list');
                } catch (UniqueConstraintViolationException $e) {
                    $this->addFlash('error', 'Ce nom d\'utilisateur existe déjà.');

                    return $this->redirectToRoute('user_list');
                }
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
    }

}
