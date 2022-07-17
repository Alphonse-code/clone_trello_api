<?php

namespace App\Controller;

use DateTime;
use App\Entity\Users;
use App\Entity\Project;
use App\Controller\ApiController;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("api/project")
 *
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
     * @Route("/list", name="app_liste", methods={"GET"})
     * @return JsonResponse
     */
    public function getProjects(): JsonResponse
    {
        // dd($this->getUser()->getId());
        if (!$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => "Seul l'admin peut voir cet contenue",
                    'code' => 401,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }
        $projects = $this->repository->findAll();

        $data = [];
        foreach ($projects as $project) {
            $data[] = [
                'project_id' => $project->getId(),
                'project_title' => $project->getTitleProject(),
                'project_type' => $project->getTypeProject(),
                'project_description' => $project->getDescriptionProject(),
                'project_status' => $project->getStatusProject(),
                'project_bigindate' => date_format(
                    $project->getBigindateProject(),
                    'Y-m-d'
                ),
                'project_enddate' => date_format(
                    $project->getEnddateProject(),
                    'Y-m-d'
                ),
                'user_id' => $project->getUser()->getId(),
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
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
        // dd($this->getUser()->getId());
        if ($this->isGranted('ROLE_USER')) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => "Seul l'admin peut voir cet contenue",
                    'code' => 401,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }

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

                $project->setUser($this->getUser());
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
    /**
     * @Route("/seul/{id}", name="app_project_id", methods={"GET"})
     *
     * @param int $id
     *@return JsonResponse
     */
    public function getPprojectById(int $id): JsonResponse
    {
        $project = $this->repository->find($id);

        if (!$project) {
            $response[] = [
                'success' => false,
                'code' => 404,
                'message' => 'Projet not found',
            ];
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }

        $data[] = [
            'project_id' => $project->getId(),
            'project_title' => $project->getTitleProject(),
            'project_type' => $project->getTypeProject(),
            'project_description' => $project->getDescriptionProject(),
            'project_status' => $project->getStatusProject(),
            'project_bigindate' => date_format(
                $project->getBigindateProject(),
                'Y-m-d'
            ),
            'project_enddate' => date_format(
                $project->getEnddateProject(),
                'Y-m-d'
            ),
            'user_id' => $project->getUser()->getId(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * Maitre à jour l'iformation du projet
     * @Route("/update_p/{id}", name="app_update_p", methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function updateProject(Request $request, $id)
    {
        // $project = $this->getPprojectById($id);
        $project = $this->repository->find($id);
        if (!$project) {
            $data = [
                'success' => false,
                'status' => 404,
                'message' => 'Project not found',
            ];
            return new JsonResponse($data, Response::HTTP_NOT_FOUND);
        }

        $request = $this->transformJsonBody($request);

        // On recuper le valeur de chaque champ
        $title = $request->get('project_title');
        $type = $request->get('project_type');
        $description = $request->get('project_description');
        $status = $request->get('project_status');
        $bigindate = $request->get('project_bigindate');
        $enddate = $request->get('project_enddate');

        $project->setTitleProject($title);
        $project->setTypeProject($type);
        $project->setDescriptionProject($description);
        $project->setStatusProject($status);
        $project->setBigindateProject(new \DateTime($bigindate));
        $project->setEnddateProject(new \DateTime($enddate));

        $this->em->flush();

        $array[] = [
            'success' => true,
            'code' => 200,
            'message' => 'Project updated successfully',
        ];
        return new JsonResponse($array, Response::HTTP_OK);

        $response = [
            'success' => false,
            'code' => 405,
            'message' => ' ---- Method Not Allowed ----',
        ];
        return new JsonResponse($response, Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @Route("/delete_p/{id}", name="app_delete_p", methods={"DELETE"})
     *
     * @param [type] $id
     * @return void
     */
    public function deleteProject(Request $request, $id)
    {
        $project = $this->repository->find($id);

        if (!$project) {
            $response = [
                'success' => false,
                'code' => 404,
                'message' => 'Projet not found',
            ];
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        if ($request->getMethod() == 'DELETE') {
            $this->em->remove($project);
            $this->em->flush();
            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Project deleted successfully',
            ];
            return new JsonResponse($response, Response::HTTP_OK);
        }
    }
}
