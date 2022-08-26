<?php

namespace App\Controller;

use App\Entity\Tableau;
use App\Controller\ApiController;
use App\Repository\CarteRepository;
use App\Repository\TableauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("api/board")
 *
 */
class TableauController extends ApiController
{
    private $em;
    private $repository;
    private $carteRepository;
    private NormalizerInterface $normalizer;
    public function __construct(
        EntityManagerInterface $em,
        TableauRepository $repository,
        CarteRepository $carteRepository,
        NormalizerInterface $normalizer
    ) {
        $this->em = $em;
        $this->repository = $repository;
        $this->carteRepository = $carteRepository;
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("/board_list", name="board_list")
     *
     * @return JsonResponse
     */
    public function getBoard(): JsonResponse
    {

        $boards = $this->repository->findAll();
        $lists = $this->normalizer->normalize($boards,null,["groups" => "read:tableau_with_card"]);
       
       return new JsonResponse($lists, Response::HTTP_OK);
    }
    

    /**
     * @Route("/create", name="app_create")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create_tableau(Request $request): JsonResponse
    {
        if ($request->getMethod() == 'POST') {
            $request = $this->transformJsonBody($request);
            $nom = $request->get('nom');
            if (!empty($nom)) {
                $tableau = new Tableau();
                $tableau->setNom($nom);
                $this->em->persist($tableau);
                $this->em->flush();
            }
        }

        $response[] = [
            'success' => true,
            'code' => 200,
            'message' => 'Board created successfully',
        ];
        return new JsonResponse($response);
    }
    /**
     * @Route("/update_b/{id}", name="app_update_b")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update_tableau(Request $request, $id): JsonResponse
    {
        if ($request->getMethod() == 'PUT') {
            $board = $this->repository->find($id);
            if (!$board) {
                $response[] = [
                    'message' => 'Could not find board',
                    'code' => 404,
                    'success' => false,
                ];
                return new JsonResponse($response, Response::HTTP_NOT_FOUND);
            }
            $request = $this->transformJsonBody($request);

            $nom = $request->get('nom');

            if (!empty($nom)) {
                $board->setNom($nom);
                $this->em->flush();
            }
            $response[] = [
                'success' => true,
                'code' => 200,
                'message' => 'Board updated successfully',
            ];
            return new JsonResponse($response);
        }

        $response = [
            'success' => false,
            'code' => 405,
            'message' => ' ---- Method Not Allowed ----',
        ];
        return new JsonResponse($response, Response::HTTP_METHOD_NOT_ALLOWED);
    }

    /**
     * @Route("/delete_b/{id}", name="app_delete_b")
     *
     * @param [type] $id
     * @return void
     */
    public function deleteBoard(Request $request, $id)
    {
        $board = $this->repository->find($id);
        if (!$board) {
            $response = [
                'success' => false,
                'code' => 404,
                'message' => 'Board not found',
            ];
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        if ($request->getMethod() == 'DELETE') {
            $this->em->remove($board);
            $this->em->flush();
            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'Board deleted successfully',
            ];
            return new JsonResponse($response, Response::HTTP_OK);
        }
    }
}
