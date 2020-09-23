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
    private $user;

    public function __construct(UserRepository $userRepository, User $user)
    {
        $this->userRepository = $userRepository;
        $this->user = $user;
    }

    /**
     * @Route("/user/add/", name="add_user", methods={"POST"})
     */
    public function addUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        $dataset = ['firstName', 'lastName', 'email', 'password', 'birthday', 'role', 'location'];

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
        $zip = $data['location'];

        // if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($birthday) || empty($role) || empty($zip)) {
        //     throw new NotFoundHttpException('Expecting mandatory parameters!');
        // }

        if ($this->userRepository->saveUser($firstName, $lastName, $email, $password, $birthday, $role, $zip)) {
            return new JsonResponse(['status' => 'User created!'], Response::HTTP_CREATED);
        }
    }

    // /**
    //  * @Route("/user/{id}", name="get_user", methods={"GET"})
    //  */
    // public function getUser(User $user, UserRepository $userRepository, SerializerInterface $serializer)
    // {
    //     $user = $userRepository->find($user->getId());


    //     $json = $serializer->serialize($user, 'json', [
    //         'groups' => ['user']
    //     ]);

    //     return new Response($json, 200, [
    //         'Content-Type' => 'application/json'
    //     ]);
    // }

   /**
     * @Route("/user/{id}", name="get_user", methods={"GET"})
     */
    public function getUser($id): Response
    {
        return $this->serializeDoctrine($this->userRepository->find($this->user->getId()), 'user');
    }


    // /**
    //  * @Route("/user/{id}", name="get_user", methods={"GET"})
    //  */
    // public function getUser($id): Response
    // {
    //     return $this->serializeDoctrine($this->userRepository->find($id), 'user');
    // }

    public function getEntity($id)
    {
        return $this->userRepository->find($id);
    }
}
