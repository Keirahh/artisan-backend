<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/user")
 */
class UserController extends ApiController
{
    private $locationController;
    private $userRepository;
    public function __construct(LocationController $locationController, UserRepository $userRepository, SerializerInterface $serializer)
    {
        $this->locationController = $locationController;
        $this->userRepository = $userRepository;
        parent::__construct($serializer);
    }


    /**
     * @Route("/add", name="add_user", methods={"POST"})
     */
    public function register(Request $request)
    {
        $data =  json_decode($request->getContent(), true);
        $firstName              = $data["firstName"];
        $lastName               = $data["lastName"];
        $birthdate              = $data["birthdate"];
        $location               = $data["location"];
        $email                  = $data["email"];
        $password               = $data["password"];
        $passwordConfirmation   = $data["password_confirmation"];
        $role                   = $data["role"];
        $errors = [];

        foreach ($dataset as $property) {
            if (empty($data[$property])) {
                throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$property] . ')');
            }
        if ($password != $passwordConfirmation) {
            $errors[] = "Password does not match the password confirmation.";
        }
        if (strlen($password) < 6) {
            $errors[] = "Password should be at least 6 characters.";
        }
        if ($errors) {
            return new JsonResponse([
                'errors' => $errors
            ], 400);
        } else {
           return $this->userRepository->saveUser($firstName, $lastName, $birthdate, $location, $email, $password, $role);
        }
    }


    /**
     * @Route("/{id}", name="get_user", methods={"GET"})
     */
    public function getUser($id): Response
    {
        return $this->serializeDoctrine($this->userRepository->find($id), 'user');
    }
}
