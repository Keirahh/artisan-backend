<?php

namespace App\Controller;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends ApiController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository, SerializerInterface $serializer)
    {
        $this->userRepository = $userRepository;
        parent::__construct($serializer);
    }

    /**
     * @Route("/user/add/", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $dataset = ['firstName', 'lastName', 'email', 'password', 'birthday', 'role'];

        foreach ($dataset as $property) {
            if (empty($data[$property])) {
                throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$property] . ')');
            }
        }

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $password = $data['password'];
        $birthday = $data['birthday'];
        $role = $data['role'];

        // if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($birthday) || empty($role) || empty($zip)) {
        //     throw new NotFoundHttpException('Expecting mandatory parameters!');
        // }

        if ($this->userRepository->saveUser($firstName, $lastName, $email, $password, $birthday, $role)) {
            return new JsonResponse(['status' => 'User created!'], Response::HTTP_CREATED);
        }
    }

   /**
     * @Route("/user/{id}", name="get_user", methods={"GET"})
     */
    public function getUser($id): Response
    {
        return $this->serializeDoctrine($this->userRepository->find($id), 'user');
    }

    public function getEntity($id)
    {
        return $this->userRepository->find($id);
    }
}
