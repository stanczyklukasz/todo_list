<?php


namespace App\Controller;


use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class TaskController
 * @package App\Controller
 */
class TaskController extends AbstractController
{
    private $TaskRepository;
    private $entityManger;

    public function __construct(
        TaskRepository $taskRepository,
        EntityManagerInterface $entityManager
    ){
        $this->TaskRepository = $taskRepository;
        $this->entityManger = $entityManager;
    }


    /**
     * @return Response
     * @Route("/", name="homepage", methods={"GET"})
     */
    public function HomePage(): Response
    {
        return $this->render('homepage.html.twig',[
            'tasks' => $this->TaskRepository->findAll()
        ]);
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/task/new", name="task_create", methods={"GET", "POST"})
     */
    public function CreateTask(Request $request): Response
    {
        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $newTask = new Task();
            $newTask
                ->setDescription($formData['description'])
                ->setPriority($formData['priority'])
                ->setIsDone(false);

            $this->entityManger->persist($newTask);
            $this->entityManger->flush();

            $this->addFlash('success', 'task_created');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @param int $taskId
     * @param Request $request
     * @return Response
     * @throws \Exception
     * @Route("/task/{taskId}/edit", name="task_edit", methods={"GET", "POST"})
     */
    public function EditTask(int $taskId, Request $request): Response
    {
        $task = $this->TaskRepository->findOneBy(['id' => $taskId]);
        if (is_null($task)) {
            throw new \Exception("Task with id: {$taskId} not found", 404);
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManger->persist($task);
            $this->entityManger->flush();

            $this->addFlash('success', 'task_edited');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('form.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * @param int $taskId
     * @return Response
     * @throws \Exception
     * @Route("/task/{taskId}}/remove", name="task_remove", methods={"DELETE"})
     */
    public function RemoveTask(int $taskId): Response
    {
        $task = $this->TaskRepository->findOneBy(['id' => $taskId]);
        if (is_null($task)) {
            throw new \Exception("Task with id: {$taskId} not found", 404);
        }

        $this->entityManger->remove($task);
        $this->entityManger->flush();

        $this->addFlash('success', 'task_removed');

        return $this->redirectToRoute('homepage');
    }
}