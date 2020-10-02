<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArtisanRepository;
use App\Security\LoginAuthenticator;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/api/user")
 */
class UserController extends ApiController
{
    private $locationController;
    private $userRepository;
    private $artisanRepository;

    public function __construct(LocationController $locationController, UserRepository $userRepository, ArtisanRepository $artisanRepository, SerializerInterface $serializer)
    {
        $this->locationController = $locationController;
        $this->userRepository = $userRepository;
        $this->artisanRepository = $artisanRepository;
        parent::__construct($serializer);
    }


    /**
     * @Route("/add", name="add_user", methods={"POST"})
     */
    public function register(Request $request)
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

            if ($password != $passwordConfirmation) {
                $errors[] = "Password does not match the password confirmation.";
            }
            if (strlen($password) < 8) {
                $errors[] = "Password should be at least 8 characters.";
            }
            if ($errors) {
                return new JsonResponse([
                    'errors' => $errors
                ], 400);
            }


            $user = $this->userRepository->saveUser($firstName, $lastName, $birthdate, $location, $email, $password, $role);

            if ($role === "2") {
                $dataset = ['siret', 'company', 'activity'];
                foreach ($dataset as $artisanProperty) {
                    if (empty($data[$artisanProperty])) {
                        throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$artisanProperty] . ')');
                    }
                }
                $siret = $data["siret"];
                $company = $data["company"];
                $activity = $data["activity"];
                $this->artisanRepository->saveArtisan($user, $siret, $company, $activity);
            }

            return true;

        }

    }


    /**
     * @Route("/{id}", name="get_user", methods={"GET"})
     */
    public function gettoto($id): Response
    {
        return $this->serializeDoctrine($this->userRepository->find($id), 'user');
    }

    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function login()
    {   //verification user dans la db
        return new JsonResponse(['result' => true]);
    }
    // /**
    //  * @Route("/profile", name="api_profile")
    //  * @IsGranted("ROLE_USER")
    //  */
    // public function profile()
    // {
    //     return new JsonResponse([
    //         'user' => $this->getUser()
    //     ], 
    //     200, 
    //     [ 'groups' => ['api']], 
    //     );
    // }
}
