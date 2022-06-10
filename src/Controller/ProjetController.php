<?php

namespace App\Controller;

use DateTime;
use App\Entity\Projet;
use App\Controller\ApiController;
use App\Repository\ProjetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/api/projet")
 */
class ProjetController extends ApiController
{
    private $em;
    private $repository;

    public function __construct(
        EntityManagerInterface $em,
        ProjetRepository $repository
    ) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * Créer nouveau projet
     *@Route("/creer", name="app_creer", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function creerProjet(Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        // On recuper le valeur de chaque champ
        $titre = $request->get('titre');
        $description = $request->get('description');
        $statut = $request->get('statut');
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');

        if (
            !empty($date_debut) ||
            !empty($date_fin) ||
            !empty($titre) ||
            !empty($description) ||
            !empty($status)
        ) {
            $projet = new Projet();
            $projet->setTitre($titre);
            $projet->setDescription($description);
            $projet->setStatut($statut);
            $projet->setDateDebut(new \DateTime($date_debut));
            $projet->setDateFin(new \DateTime($date_fin));
            $this->em->persist($projet);
            $this->em->flush();
            $array = [
                'success' => true,
                'code' => 200,
                'message' => 'Project created successfully',
            ];
            return new JsonResponse($array, Response::HTTP_OK);
        }
        $array = [
            'success' => false,
            'code' => 400,
            'message' => 'Champ invalid',
        ];
        return new JsonResponse($array, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Liste de tous le projet
     *@Route("/list", name="app_list", methods={"GET"})
     * @return JsonResponse
     */
    public function getAllProjet(): JsonResponse
    {
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
        $projets = $this->repository->findAll();
        $data = [];
        foreach ($projets as $projet) {
            $data[] = [
                'projet_id' => $projet->getId(),
                'projet_titre' => $projet->getTitre(),
                'projet_description' => $projet->getDescription(),
                'projet_statut' => $projet->getStatut(),
                'projet_datedebut' => $projet->getDateDebut(),
                'projet_datefin' => $projet->getDateFin(),
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * Maitre à jour l'iformation du projet
     *@Route("/update/{id}", name="app_update", methods={"PUT","POST"})
     * @param Request $request
     * @return void
     */
    public function updateProjet(Request $request, $id)
    {
        $projet = $this->repository->find($id);

        if (!$projet) {
            $data = [
                'success' => false,
                'status' => 404,
                'message' => 'Projet n\existe pas ',
            ];
            return new JsonResponse($data, Response::HTTP_NOT_FOUND);
        }

        $request = $this->transformJsonBody($request);
        // On recuper le valeur de chaque champ
        $titre = $request->get('titre');
        $description = $request->get('description');
        $statut = $request->get('statut');
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');

        $projet->setTitre($titre);
        $projet->setDescription($description);
        $projet->setStatut($statut);
        $projet->setDateDebut(new \DateTime($date_debut));
        $projet->setDateFin(new \DateTime($date_fin));
        $this->em->flush();

        $response = [
            'success' => true,
            'code' => 200,
            'message' => 'Project updated successfully',
        ];

        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @Route("/delete/{id}", name="app_delete", methods={"DELETE"})
     *
     * @param [type] $id
     * @return void
     */
    public function deleteUser($id)
    {
       
        $projet = $this->repository->find($id);
        if (!$projet) {
            $response = [
                'success' => false,
                'code' => 404,
                'message' => 'Projet not found',
            ];
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        $this->em->remove($projet);
        $this->em->flush();
        $response = [
            'success' => true,
            'code' => 200,
            'message' => 'Projet deleted successfully',
        ];
        return new JsonResponse($response, Response::HTTP_OK);
    }
}
