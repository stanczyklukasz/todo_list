<?php


namespace App\Controller\Task;


use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CreateController
 * @package App\Controller\Task
 * @Route("/task")
 */
class CreateController extends AbstractController
{

    private $entityManger;

    public function __construct(
        EntityManagerInterface $entityManager
    ){
        $this->entityManger = $entityManager;
    }


    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="task_create", methods={"GET", "POST"})
     */
    public function CreateTask(Request $request): Response
    {
        $form = $this->createForm(TaskType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $newTask = new Task(
                $formData['description'],
                $formData['priority']
            );

            $this->entityManger->persist($newTask);
            $this->entityManger->flush();

            $this->addFlash('success', 'task_created');
            return $this->redirectToRoute('homepage');
        }

        return $this->render('form.html.twig', [
            'form' => $form->createView()
        ]);
    }

}