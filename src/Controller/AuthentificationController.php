<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Component\Mime\Email;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

//header('Access-Control-Allow-Origin: *');
class AuthentificationController extends ApiController
{
    private $em;
    private $repository;

    public function __construct(
        EntityManagerInterface $em,
        UsersRepository $repository
    ) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * registration fonction require username,password and valid email
     *
     * @Route("/api/register", name="register", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     *
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, SluggerInterface $slugger): JsonResponse {
        $request = $this->transformJsonBody($request);
       

        $username = $request->get('username');
        $password = $request->get('password');
        $email = $request->get('email');
        $roles = $request->get('roles');
        
        $images = $request->get('image');
    
        $originalFilename = pathinfo($image->getClientOriginalFilename(), PATHINFO_FILENAME);
        $newFilename = $slugger->slug($originalFilename);
        $newFilename = $newFilename.'-'.uniqid().'.'.$image->guessExtension();

        try {
            $image->move($this->$this->getParameter('images_directory'), $newFilename
                );
            }catch(FileException $e) {}
        
        if (empty($username) || empty($password) || empty($email)) {
            $array = [
                'success' => false,
                'code' => 422,
                'message' => 'Invalid username or password or email address',
            ];
            return new JsonResponse(
                $array,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
        $exist_email = $this->repository->check_email($email);
        $exist_username = $this->repository->check_username($username);

        if (!$exist_email && !$exist_username) {
            $user = new Users($username);
            $user->setPassword($encoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setRoles($roles);
            $user->setImage($newFilename);
            $this->em->persist($user);
            $this->em->flush();
            $array = [
                'success' => true,
                'code' => 200,
                'message' => 'Users created successfully',
            ];
            return new JsonResponse($array, Response::HTTP_OK);
        }
        $array = [
            'success' => false,
            'code' => 500,
            'message' => 'Users existing from database',
        ];
        return new JsonResponse($array, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Route("/api/login_check", name="login_check", methods={"POST"})
     * @param UserInterface $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function login(UserInterface $user,JWTTokenManagerInterface $JWTManager): JsonResponse {
        if (null !== $user) {
            $array = [
                'token' => $JWTManager->create($user),
                'code' => 200,
                'message' => 'Login successful',
            ];
            return new JsonResponse($array, Response::HTTP_OK);
        }
    }

    /**
     * @Route("/api/users", name="employer", methods={"GET"})
     *
     * @param UsersRepository $repository
     * @return JsonResponse
     */
    public function getAllUsers(): JsonResponse
    {
       /* if (!$this->isGranted('ROLE_USER')) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => "Seul l'admin peut voir cet contenue",
                    'code' => 401,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }*/

        $users = $this->repository->findAll();
        
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'user_id' => $user->getId(),
                'user_username' => $user->getUsername(),
                'user_email' => $user->getEmail(),
                'user_password' => $user->getPassword(),
                'user_photo' => $user->getImage(),
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("/forgot_password", name="forgot_password", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return JsonResponse
     */
    public function forgotPasswordAction(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer
    ): JsonResponse {
        $request = $this->transformJsonBody($request);
        $email = $request->get('username');
        $user = $this->repository->findOneBy(['username' => $email]);
        if (!$user) {
            $array = [
                'success' => false,
                'code' => 404,
                'message' => 'Email non trouvé dans la base de données',
            ];
            return new JsonResponse($array, Response::HTTP_NOT_FOUND);
        } else { 
            $token = $tokenGenerator->generateToken();
            $em = $this->getDoctrine()->getManager();
            $user->setForgotPasswordToke($token);
            $em->flush();
            $email = (new Email())
                ->from('solofondraibedani@gmail.com')
                ->to($email)
                ->subject('Mot de passe oublier')
                ->text('Mot de passe oublier')
                ->html("
            <h3>Veuillez-trouvez ci- après le lien pour reinitialiser votre mot de passe</h3> 
             <h3 style='color:blue; font-size: 20px; font-weight: bold; text-decoration: none;'>
            <a href='http://localhost:3000/new_password/$token'>Cliquez-ici pour réinitialiser</a></h3>"
                );
            $mailer->send($email);
            $array = [
                'success' => true,
                'message' => 'E-mail envoyé avec succès',
                'code' => 200,
                'access_token' => $token,
            ];
            return new JsonResponse($array, JsonResponse::HTTP_OK);
        }
    }
    /**
     * Apres forgot password, creer nouveau password
     *
     * @Route("/new_password/{token}", name="new_password", methods={"POST"})
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return JsonResponse
     */
    public function new_password( string $token,Request $request,UserPasswordEncoderInterface $encoder): JsonResponse {
        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->transformJsonBody($request);

        $new_password = $request->get('password');
        if (empty($new_password)) {
            return $this->respondValidationError(
                'Invalid Password '
            );
        }
        $user = $this->repository->findOneBy(['forgotPasswordToke' => $token]);
        $user->setForgotPasswordToke(null);
        $user->setPassword($encoder->encodePassword($user, $new_password));
        $entityManager->flush();
        $array = [
            'success' => true,
            'code' => 200,
            'message' => 'Mots de passe bien reinitialiser',
        ];
        return new JsonResponse($array);
    }

    /**
     *
     * @Route("/reset", name="app_reset_password", methods="POST")
     * @param UserRepository $repository
     * @param Request $request
     * @return JsonResponse
     */
    public function reset_password(Request $request, string $token,UserPasswordEncoderInterface $passwordEncoder): JsonResponse {
        $entityManager = $this->getDoctrine()->getManager();
        $request = $this->transformJsonBody($request);
        $token = $request->get('token');
        $new_password = $request->get('new_password');
        $user = $this->repository->findOneBy(['passwordResetToken' => $token]);
        if (empty($token) || empty($newpassword)) {
            return $this->respondValidationError('Token invalide ou mot de passe');
        }
        if ($user === null) {
            return new JsonResponse('Utilisateur inexistant ');
        }
        $user->setResetToken(null);
        $user->setPassword($encoder->encodePassword($user, $new_password));
        $entityManager->flush();

        $this->addFlash('notice', 'Mot de passe mis à jour !');

        return new JsonResponse('Utilisateur existant ');
    }


    /**
     * @Route("/api/updateUser/{id}", name="updateUser", methods={"PUT", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param UsersRepository $usersRepository
     * @return void
     */
    public function updateUser(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        UsersRepository $usersRepository,
        $id
    ) {
        $user = $usersRepository->find($id);

        try {
            if (!$user) {
                $data = [
                    'success' => false,
                    'status' => 404,
                    'message' => 'Utilisateur n\existe pas ',
                ];
                return new JsonResponse($data);
            }

            $request = $this->transformJsonBody($request);

            if (
                !$request ||
                !$request->get('username') ||
                !$request->get('password') ||
                !$request->get('email')
            ) {
                throw new \Exception();
            }
            $user->setEmail($request->get('email'));
            $user->setPassword(
                $encoder->encodePassword($user, $request->get('password'))
            );
            $user->setUsername($request->get('username'));
            $entityManager->flush();

            $response = [
                'success' => true,
                'code' => 200,
                'message' => 'User updated successfully',
            ];

            return new JsonResponse($response, Response::HTTP_OK);
        } catch (\Exceptions\UserNotFoundException $th) {
            $response = [
                'success' => false,
                'code' => 422,
                'message' => 'Data not valid',
            ];
            return new JsonResponse(
                $response,
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @Route("/api/deleteUser/{id}", name="deleteUser", methods={"DELETE"})
     *
     * @param EntityManagerInterface $entityManager
     * @param UsersRepository $repository
     * @param [type] $id
     * @return void
     */
    public function deleteUser(
        EntityManagerInterface $entityManager,
        UsersRepository $repository,
        $id
    ) {
        if (
            !$this->isGranted('ROLE_ADMIN') ||
            !$this->isGranted('ROLE_SUPER_ADMIN')
        ) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' =>
                        "Seul l'admin ou le super admin peut faire cet operation",
                    'code' => 401,
                ],
                Response::HTTP_UNAUTHORIZED
            );
        }
        $user = $repository->find($id);
        if (!$user) {
            $response = [
                'success' => false,
                'code' => 404,
                'message' => 'User not found',
            ];
            return new JsonResponse($response, Response::HTTP_NOT_FOUND);
        }
        $entityManager->remove($user);
        $entityManager->flush();
        $response = [
            'success' => true,
            'code' => 200,
            'message' => 'User deleted successfully',
        ];
        return new JsonResponse($response, Response::HTTP_OK);
    }

    /**
     * @Route("/api/users_image", name="user_image")
     */
    public function img_usr(): JsonResponse
    {
        $usr_img = $this->repository->get_allImg();
        
        $data = [];
        foreach ($usr_img as $user) {
            $data[] = [
                'id' => $user['id'],
                'name' => $user['email'],
                'image' => $user['image'],
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
