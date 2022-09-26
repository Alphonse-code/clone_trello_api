<?php

namespace App\Controller;

use App\Entity\Tasks;
use App\Controller\ApiController;
use App\Repository\CarteRepository;
use App\Repository\TasksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("api/tasks")
 */
class TasksController extends ApiController
{
    private $em;
    private $carte_repos;
    private $task_repos;

    public function __construct(EntityManagerInterface $em, CarteRepository $carte_repos, TasksRepository $task_repos)
    {
        $this->em = $em;
        $this->carte_repos = $carte_repos;
        $this->task_repos = $task_repos;
    }

    /**
     * @Route("/create_task", name="app_create_task")
     */
    public function create_task(Request $request): JsonResponse
    {
        /*if ($this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => "Seul l'admin peut créer de tache",
                    'code' => 401,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }*/
       
        $request = $this->transformJsonBody($request);
        $copleted = false;
        $text = $request->get('text');
        $cartes_id= $request->get('cartes_id');

        $cartes = $this->carte_repos->find($cartes_id);

        if (!empty($text) || !empty($cartes_id)) {
            
            $tasks = new Tasks();
            $tasks->setCartes($cartes);
            $tasks->setText($text);
            $tasks->setCopleted($copleted);

            $this->em->persist($tasks);
            $this->em->flush();
            $array[] = ['success' => true,'code' => 200,'message' => 'Tasks created successfully'];
            return new JsonResponse($array, Response::HTTP_OK);
        }
    }

    /**
     * @Route("/delete_task/{id}", name="task_delete")
     */
    public function remove_task(Request $request, $id): JsonResponse
    {
        $task = $this->task_repos->find($id);
        $this->em->remove($task);
        $this->em->flush();
        $array[] = ['success' => true,'code' => 200,'message' => 'Tasks deleted successfully'];
        return new JsonResponse($array, Response::HTTP_OK);
    }
      /**
     * @Route("/update_task/{id}", name="update_task")
     */
    public function update_task(Request $request, $id): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $task = $this->task_repos->find($id);
        $completed = $request->get('completed');
        
        $task->setCopleted($completed);
        $this->em->flush();
        $array[] = ['success' => true,'code' => 200,'message' => 'Tasks updated successfully'];
        return new JsonResponse($array, Response::HTTP_OK);
    }
}