<?php

namespace App\Controller;

use App\Repository\ArtisanRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api/user")
 */
class UserController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var ArtisanRepository
     */
    private $artisanRepository;

    /**
     * UserController constructor.
     * @param LocationController $locationController
     * @param UserRepository $userRepository
     * @param ArtisanRepository $artisanRepository
     */
    public function __construct(LocationController $locationController, UserRepository $userRepository, ArtisanRepository $artisanRepository)
    {
        $this->locationController = $locationController;
        $this->userRepository = $userRepository;
        $this->artisanRepository = $artisanRepository;
    }


    /**
     * @Route("/add", name="user_register", methods={"POST"})
     */
    public function addUser(Request $request)
    {
        $data =  json_decode($request->getContent(), true);

        $errors = [];

        $dataset = ['firstName', 'lastName', 'birthdate', 'location', 'email', 'password', 'password_confirm', 'role'];

        foreach ($dataset as $property) {
            if (empty($data[$property])) {
                throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$property] . ')');
            }

            $firstName = $data["firstName"];
            $lastName = $data["lastName"];
            $birthdate = $data["birthdate"];
            $location = $data["location"];
            $email = $data["email"];
            $password = $data["password"];
            $passwordConfirmation = $data["password_confirm"];
            $role = $data["role"];

            if($role === "2")
            {
                $dataset = ['siret', 'company', 'activity'];

                if (empty($data[$property])) {
                    throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$property] . ')');
                }
            }

            if ($password != $passwordConfirmation)
            {
                $errors[] = "Password does not match the password confirmation.";
            }

            if (strlen($password) < 8)
            {
                $errors[] = "Password should be at least 8 characters.";
            }

            if ($errors)
            {
                return new JsonResponse([
                    'errors' => $errors
                ], 400);
            }

            $user = $this->userRepository->saveUser($firstName, $lastName, $birthdate, $location, $email, $password, $role);

            if ($role === "2")
            {
                $siret = $data["siret"];
                $company = $data["company"];
                $activity = $data["activity"];

                $this->artisanRepository->saveArtisan($user, $siret, $company, $activity);
            }

            return new JsonResponse([
                'status' => "Success"
            ], 200);
        }

    }

    /**
     * @Route("/{id}", name="get_user", methods={"GET"})
     */
    public function user($id, ApiController $apiController): Response
    {
        return $apiController->serializeDoctrine($this->userRepository->find($id), 'user');
    }

    
    public function getEntity($id)
    {
        return $this->userRepository->find($id);
    }
//affichage login
    /**
     * @Route("/login", name="user_login", methods={"POST"})
     */
    public function login()
    {
        return $this->json(['result' => true]);
    }

//affichage user
    /**
     * @Route("/profile", name="user_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile()
    {
        return $this->json([
            'user' => $this->getUser()
        ], 200, [], [
            'groups' => ['log']
        ]);
    }

//redirection page index
    /**
     * @Route("/", name="user_home")
     */
    public function home()
    {
        return $this->json(['result' => true]);
    }
}
