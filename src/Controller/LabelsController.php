<?php

namespace App\Controller;

use App\Entity\Labels;
use App\Controller\ApiController;
use App\Repository\CarteRepository;
use App\Repository\LabelsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("api/labels")
 */
class LabelsController extends ApiController
{
    private $em;
    private $carte_repos;
    private $labels_repos;

    public function __construct(EntityManagerInterface $em, CarteRepository $carte_repos, LabelsRepository $labels_repos)
    {
        $this->em = $em;
        $this->carte_repos = $carte_repos;
        $this->labels_repos = $labels_repos;
    }

    /**
     * @Route("/add_labels", name="app_add_labels")
     */
    public function addLabels(Request $request): JsonResponse
    {
        $request = $this->transformJsonBody($request);
        $color = $request->get('color');
        $text = $request->get('text');
        $cartes_id = $request->get('cartes_id');
        $cartes = $this->carte_repos->find($cartes_id);
        $lab = new Labels();
        $lab->setCartes($cartes);
        $lab->setColor($color);
        $lab->setText($text);

        $this->em->persist($lab);
        $this->em->flush();
        $array[] = ['success' => true,'code' => 200,'message' => 'Labels added successfully'];
        return new JsonResponse($array, Response::HTTP_OK);
    }

    /**
     * @Route("/remove_labels/{id}", name="app_remove_labels")
     */
    public function removeLabels(Request $request, $id): JsonResponse
    {
        $label = $this->labels_repos->find($id);
       
        $this->em->remove($label);
        $this->em->flush();
        $array[] = ['success' => true,'code' => 200,'message' => 'Labels deleted successfully'];
        return new JsonResponse($array, Response::HTTP_OK);
    }
    
}
