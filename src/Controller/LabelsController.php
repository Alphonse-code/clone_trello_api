<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LabelsController extends AbstractController
{
    /**
     * @Route("/labels", name="app_labels")
     */
    public function index(): Response
    {
        return $this->render('labels/index.html.twig', [
            'controller_name' => 'LabelsController',
        ]);
    }
}
