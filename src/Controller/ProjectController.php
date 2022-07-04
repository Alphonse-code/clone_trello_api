<?php

namespace App\Controller;

use DateTime;
use App\Entity\Project;
use App\Controller\ApiController;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/project")
 */
class ProjectController extends ApiController
{
    private $em;
    private $repository;

    public function __construct(
        EntityManagerInterface $em,
        ProjectRepository $repository
    ) {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * for create new project you needs title,type, description,status, bigindate and enddate
     * @Route("/create", name="app_create")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create_project(Request $request): JsonResponse
    {
        if ($request->getMethod() == 'POST') {
            $request = $this->transformJsonBody($request);
            // On recuper le valeur de chaque champ
            $title = $request->get('title');
            $type = $request->get('type');
            $description = $request->get('description');
            $status = $request->get('status');
            $bigindate = $request->get('bigindate');
            $enddate = $request->get('enddate');

            if (
                !empty($description) ||
                !empty($bigindate) ||
                !empty($enddate) ||
                !empty($title) ||
                !empty($status) ||
                !empty($enddate)
            ) {
                $project = new Project();
                $project->setTitleProject($title);
                $project->setTypeProject($type);
                $project->setDescriptionProject($description);
                $project->setStatusProject($status);
                $project->setBigindateProject(new \DateTime($bigindate));
                $project->setEnddateProject(new \DateTime($enddate));
                $this->em->persist($project);
                $this->em->flush();
                $array[] = [
                    'success' => true,
                    'code' => 200,
                    'message' => 'Project created successfully',
                ];
                return new JsonResponse($array, Response::HTTP_OK);
            }
            $array[] = [
                'success' => false,
                'code' => 400,
                'message' => 'Champ invalid',
            ];
            return new JsonResponse($array, Response::HTTP_BAD_REQUEST);
        }
    }
}
