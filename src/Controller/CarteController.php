<?php

namespace App\Controller;

use App\Entity\Carte;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Tableau;
use App\Controller\ApiController;
use App\Repository\CarteRepository;
use App\Repository\UsersRepository;
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
   private $boardRepository;
   private $userRepository;

    public function __construct(
        EntityManagerInterface $em,
        CarteRepository $carteRepository,
        TableauRepository $boardRepository,
        UsersRepository $userRepository
        
    ) {
        $this->em = $em;
        $this->carteRepository = $carteRepository;
        $this->boardRepository = $boardRepository;
        $this->userRepository = $userRepository;
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
                'code' => 500,
                'message' => 'Handled errors occurred during created cards',
            ];
            return new JsonResponse($response);
        }
    }

    /**
     * @Route("/update_date/{id}", name="app_update_card" , methods={"PUT"})
     */
    public function update_date(Request $request, $id)
    {
        $carte = $this->carteRepository->find($id);

        $request = $this->transformJsonBody($request);
        $date = $request->get('date');
        $carte->setDate($date);
        $this->em->flush();
        $response[] = [
            'success' => true,
            'code' => 200,
            'message' => 'Cards updated successfully',
        ];
        return new JsonResponse($response, Response::HTTP_OK);
    
    }

    /**
     * @Route("/update_desc/{id}", name="app_update_desc" , methods={"PUT"})
     */
    public function update_desc(Request $request, $id)
    {
        $carte = $this->carteRepository->find($id);

        $request = $this->transformJsonBody($request);
        $desc = $request->get('desc');
        $carte->setDescription($desc);
        $this->em->flush();
        $response[] = [
            'success' => true,
            'code' => 200,
            'message' => 'Cards updated successfully',
        ];
        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @Route("/remove_card/{id}", name="remove_card")
     */
    public function remove_card(Request $request, $id): JsonResponse
    {
        $carte = $this->carteRepository->find($id);
        $this->em->remove($carte);
        $this->em->flush();
        $array[] = ['success' => true,'code' => 200,'message' => 'Card deleted successfully'];
        return new JsonResponse($array, Response::HTTP_OK);

    }
    
    /**
     * @Route("/drag_and_drop/{id}", name="drag_and_drop")
     * 
     */
    public function drag_and_drop(Request $request, $id): JsonResponse
    {
        $carte = $this->carteRepository->find($id);
        $request = $this->transformJsonBody($request);
        $boardId = $request->get('tableau_id');
        $board = $this->boardRepository->find($boardId);
        $carte->setTableau($board);
        $this->em->flush();
        $array[] = [
            'success' => true,
            'code' => 200,
            'message' => 'Card draged successfully'
        ];
        return new JsonResponse($array, Response::HTTP_OK);
    }

    /**
     * @Route("/assign_card/{id}", name="assign_card")
     */
    public function assigner_tache(Request $request, $id, MailerInterface $mailer): JsonResponse
    {
        $carte = $this->carteRepository->find($id);
        
        $request = $this->transformJsonBody($request);
        $user_id = $request->get('user_id');
        $user = $this->userRepository->find($user_id);
        $email = $user->getUsername();
        $email = (new Email())
                ->from('solofondraibedani@gmail.com')
                ->to($email)
                ->subject('NOTIFICATION | GESTION DE PROJET')
                ->text('Invitation sur le projet ICamSock')
                ->html("
            <h3> Vous êtez assigner à un tâche sur le projet ICamSock</h3> 
             <h3 style='color:blue; font-size: 20px; font-weight: bold; text-decoration: none;'>
            <a href='http://localhost:3000'>Cliquez-ici pour accéder à l'application</a></h3>"
            );
        $mailer->send($email);
        $carte->setUsers($user);
        $this->em->flush();
        
        $array[] = [
            'success' => true,
            'code' => 200,
            'message' => 'Card assigned successfully'
        ];
        return new JsonResponse($array, Response::HTTP_OK);
    }
}
