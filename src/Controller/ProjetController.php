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
    public function creerProjet(
        ProjetRepository $repository,
        Request $request
    ): JsonResponse {
        $request = $this->transformJsonBody($request);
        // On recuper le valeur de chaque champ
        $titre = $request->get('titre');
        $description = $request->get('description');
        $statut = $request->get('statut');
        $date_debut = $request->get('date_debut');
        $date_fin = $request->get('date_fin');

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
}
