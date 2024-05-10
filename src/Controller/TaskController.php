<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class TaskController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/tasks', name: 'task_list', methods: ['GET','POST'])]
    public function listAction(TaskRepository $task)
    {
        return $this->render('task/list.html.twig', ['tasks' => $task->findAll()]);
    }

    #[Route('/tasks/create', name: 'task_create', methods: ['GET','POST'])]
    public function createAction(Request $request, Security $security)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            
            $task->setUser($user);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit', methods: ['GET','POST'])]
    public function editAction(Task $task, Request $request, Security $security)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $security->getUser();
            
            $task->setUser($user);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle', methods: ['GET','POST'])]
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->entityManager->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['GET','POST'])]
    public function deleteTaskAction(Task $task): Response
    {
        $taskCreatorId = $task->getUser()->getId();
        $taskCreator = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $taskCreatorId]);
        $currentUser = $this->getUser();
        
        if ($this->isGranted('ROLE_ADMIN')) {
            if (in_array('ROLE_ANONYMOUS', $taskCreator->getRoles())) {
                // Si l'utilisateur a les permissions nécessaires, supprimer la tâche
                $this->entityManager->remove($task);
                $this->entityManager->flush();
            
                $this->addFlash('success', 'La tâche a bien été supprimée.');
            
                return $this->redirectToRoute('task_list');
            } else {
                $this->addFlash('error', 'Vous n\'avez pas les permissions nécessaires pour supprimer cette tâche.');
                return $this->redirectToRoute('task_list');
            }
        } else {
            // Si l'utilisateur n'est pas ADMIN, il ne peut supprimer que les tâches qu'il a créées lui-même
            if ($taskCreator !== $currentUser) {
                $this->addFlash('error', 'Vous n\'avez pas les permissions nécessaires pour supprimer cette tâche.');
                return $this->redirectToRoute('task_list');
            }
        }
        
        // Si l'utilisateur a les permissions nécessaires, supprimer la tâche
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
