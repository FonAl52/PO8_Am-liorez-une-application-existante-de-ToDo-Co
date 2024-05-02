<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class UserController extends AbstractController
{
    #[Route('/users', name: 'user_list', methods: ['GET','POST'])]
    public function listAction(
        UserRepository $user
        ): Response {
            return $this->render('user/list.html.twig', ['users' => $user->findAll()]);
    }
    
    #[Route('/users/create', name: 'user_create', methods: ['GET','POST'])]
    public function createAction(
        Request $request, 
        EntityManagerInterface $em,
    ): Response {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
            $user = $form->getData();
            
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");

            return $this->redirectToRoute('user_list');
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Ce nom d\'utilisateur existe déjà.');
                
                return $this->redirectToRoute('user_create');
            }
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/users/{id}/edit', name: 'user_edit', methods: ['GET','POST'])]
    public function editAction(
        User $user, 
        Request $request, 
        EntityManagerInterface $em,  
        UserPasswordHasherInterface $passwordHasher
    ): Response {
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
