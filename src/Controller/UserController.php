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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

/**
 * @Route("/api/user")
 */
class UserController extends ApiController
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
     * @param SerializerInterface $serializer
     */
    public function __construct(LocationController $locationController, UserRepository $userRepository, ArtisanRepository $artisanRepository, SerializerInterface $serializer)
    {
        $this->locationController = $locationController;
        $this->userRepository = $userRepository;
        $this->artisanRepository = $artisanRepository;
        parent::__construct($serializer);
    }


    /**
     * Create User
     * 
     * @OA\Parameter(
     *     name="firstName",
     *      in="query",
     *     description="The field used to set firstname",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="lastName",
     *     in="query",
     *     description="The field used to set lastname",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="birthdate",
     *     in="query",
     *     description="The field used to set birthdate",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="location",
     *     in="query",
     *     description="The field used to set location",
     *     @OA\Schema(type="int")
     * )
     * @OA\Parameter(
     *     name="email",
     *     in="query",
     *     description="The field used to set email",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="password",
     *     in="query",
     *     description="The field used to set password",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="password_confirm",
     *     in="query",
     *     description="The field used to set password_confirm",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="role",
     *     in="query",
     *     description="The field used to set role",
     *     @OA\Schema(type="int")
     * )
     * @Route("/add", name="user_register", methods={"POST"})
     * 
     */
    public function addUser(Request $request)
    {
        $data = json_decode($request->getContent(), true);


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

            if ($role === "2") {
                $dataset = ['siret', 'company', 'activity'];

                if (empty($data[$property])) {
                    throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$property] . ')');
                }
            }

            if ($password != $passwordConfirmation) {
                $errors[] = "Password does not match the password confirmation.";
            }

            if (strlen($password) < 8) {
                $errors[] = "Password should be at least 8 characters.";
            }

            if ($errors) {
                return new JsonResponse([
                    'errors' => $errors,
                    'result' => false
                ], 400);
            }

            $user = $this->userRepository->saveUser($firstName, $lastName, $birthdate, $location, $email, $password, $role);

            if ($role === "2") {
                $siret = $data["siret"];
                $company = $data["company"];
                $activity = $data["activity"];

                $this->artisanRepository->saveArtisan($user, $siret, $company, $activity);
            }

            return new JsonResponse([
                'status' => "Success",
                'result' => true
            ], 200);
        }
    }
    /**
     * Get User by Id
     * 
     * It is necessary to have the identification token to be able to retrieve the user
     * 
     * @Route("/{id}", name="get_user", methods={"GET"})
     */
    public function user($id): Response
    {
        return $this->serializeDoctrine($this->userRepository->find($id), 'user');
    }


    /**
     * @param $id
     * @return \App\Entity\User|null
     */
    public function getEntity($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Get user by email and password
     * 
     * It is necessary to have the identification token to be able to retrieve the user
     * 
     * @OA\Parameter(
     *     name="email",
     *     in="query",
     *     description="The field used to get user",
     *     @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *     name="password",
     *     in="query",
     *     description="The field used to get user",
     *     @OA\Schema(type="string")
     * )
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data["email"];
        $password = $data["password"];

        $rst = $this->userRepository->findOneBy(['email' => $email]);

        if ($rst) {
            $valid = $passwordEncoder->isPasswordValid($rst, $password);

            if ($valid) {
                return new JsonResponse([
                    'result' => true,
                    'user' => json_decode($this->serializeDoctrineRaw($rst, "log"))
                ]);
            }
        }

        return new JsonResponse([
            'result' => false,
        ]);
    }
}
