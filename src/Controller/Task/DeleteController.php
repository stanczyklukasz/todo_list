<?php


namespace App\Controller\Task;


use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DeleteController
 * @package App\Controller\Task
 * @Route("/task")
 */
class DeleteController extends AbstractController
{
    private $entityManager;
    private $TaskRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        TaskRepository $taskRepository
    ){
        $this->entityManager = $entityManager;
        $this->TaskRepository = $taskRepository;
    }

    /**
     * @param int $taskId
     * @return Response
     * @throws \Exception
     * @Route("/{taskId}}/remove", name="task_remove", methods={"DELETE"})
     */
    public function RemoveTask(int $taskId): Response
    {
        $task = $this->TaskRepository->findOneBy(['id' => $taskId]);
        if (is_null($task)) {
            throw new \Exception("Task with id: {$taskId} not found", 404);
        }

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        $this->addFlash('success', 'task_removed');

        return $this->redirectToRoute('homepage');
    }
}