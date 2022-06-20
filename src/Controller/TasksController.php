<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Form\ActionsType;
use App\Form\TasksType;
use App\Repository\TasksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tasks")
 */
class TasksController extends AbstractController
{
    /**
     * @Route("/", name="app_tasks_index", methods={"GET"})
     */
    public function index(TasksRepository $tasksRepository): Response
    {
        $userId = $this->getUser()->getId();
        return $this->render('tasks/index.html.twig', [
            'tasks' => $tasksRepository->findByUserId($userId),
            'localisation' => 'task index'
        ]);
    }

    /**
     * @Route("/archived", name="app_tasks_archived", methods={"GET"})
     */
    public function showArchived(TasksRepository $tasksRepository): Response
    {
        $userId = $this->getUser()->getId();
        return $this->render('tasks/archived.html.twig', [
            'archivedTasks' => $tasksRepository->isArchived($userId),
            'localisation' => 'archived tasks'
        ]);
    }

    /**
     * @Route("/new", name="app_tasks_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TasksRepository $tasksRepository): Response
    {
        $user = $this->getUser();
        $task = new Tasks();
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($user);
            $tasksRepository->add($task, true);

            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tasks/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_tasks_show", methods={"GET"})
     */
    public function show(Tasks $task): Response
    {
        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_tasks_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        $form = $this->createForm(TasksType::class, $task);
        $form->handleRequest($request);
        $formActions = [];
        foreach ($task->getActions() as $action) {
            $formAction = $this->createForm(ActionsType::class, $action);
            $formActions[] = $formAction;
            unset($formAction);
        }
        if ($form->isSubmitted() && $form->isValid()) {
            dump($form);die;
            $tasksRepository->add($task, true);

            return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('tasks/edit.html.twig', [
            'task' => $task,
            'form' => $form,
            'formActions' => $formActions
        ]);
    }

    /**
     * @Route("/{id}", name="app_tasks_delete", methods={"POST"})
     */
    public function delete(Request $request, Tasks $task, TasksRepository $tasksRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
            $tasksRepository->remove($task, true);
        }

        return $this->redirectToRoute('app_tasks_index', [], Response::HTTP_SEE_OTHER);
    }
}
