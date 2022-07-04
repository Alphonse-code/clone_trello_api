<?php

namespace App\Controller;

use App\Controller\ApiController;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/task")
 */
class TaskController extends ApiController
{
   private $em;
   private $repository;

   public function __construct(EntityManagerInterface $em, TaskRepository $repository)
   {
    $this->em = $em;
    $this->repository = $repository;
   }

   

}
