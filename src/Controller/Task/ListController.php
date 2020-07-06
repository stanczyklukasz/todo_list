<?php


namespace App\Controller\Task;


use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class TaskController
 * @package App\Controller
 */
class ListController extends AbstractController
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
        $tasks = $this->TaskRepository->findBy([],[
           'priority' => 'DESC'
        ]);

        return $this->render('homepage.html.twig',[
            'tasks' => $tasks
        ]);
    }
}