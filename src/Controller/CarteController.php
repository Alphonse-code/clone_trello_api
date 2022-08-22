<?php

namespace App\Controller;

use App\Entity\Carte;
use App\Entity\Tableau;
use App\Controller\ApiController;
use App\Repository\CarteRepository;
use App\Repository\TableauRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("api/carte")
 *
 */
class CarteController extends ApiController
{
    private $em;
    private $repository;
    private $carteRepository;
    private $tableauRepository;
    public function __construct(
        EntityManagerInterface $em,
        CarteRepository $carteRepository
        
    ) {
        $this->em = $em;
        $this->carteRepository = $carteRepository;
    }

    /**
     * @Route("/list_carte", name="app_list_carte")
     */
    public function index(): Response
    {
        
    }
     /**
     * @Route("/create_carte", name="app_create_carte")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create_carte(Request $request, ManagerRegistry $manager): JsonResponse
    {
        if ($request->getMethod() == 'POST') {
            $request = $this->transformJsonBody($request);
            $titre = $request->get('title');
            $board = $request->get('tableau_id');
            $tab = $manager->getRepository(Tableau::class)->find($board);
            //dd($tab);
            if (!empty($titre)) {
                $carte = new Carte();
                $carte->setTitle($titre);
                $carte->setTableau($tab);
                $this->em->persist($carte);
                $this->em->flush();
                $response[] = [
                    'success' => true,
                    'code' => 200,
                    'message' => 'Cards created successfully',
                ];
                return new JsonResponse($response);
            }
            $response[] = [
                'success' => false,
                'code' => 200,
                'message' => 'Handled errors occurred during created cards',
            ];
            return new JsonResponse($response);
        }
    }

    /**
     * @Route("/update_card/{id}", name="app_update_card" , methods={"PUT"})
     */
    public function update_card(Request $request, $id)
    {
        $carte = $this->carteRepository->find($id);
        
    }
}
