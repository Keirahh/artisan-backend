<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController
{

    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }

    public function serializeDoctrine($response, $entity): Response
    {
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $json = $serializer->serialize($response, 'json', [
            'groups' => [$entity]
        ]);

        return new Response($json, 200, [
            'Content-Type' => 'application/json'
        ]);
    }

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

    // public function serializeDoctrine($response, $entity): Response
    // {

    //     $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
    //     $json = $serializer->serialize($response, 'json', [
    //         'groups' => [$entity]
    //     ]);
    //     // $json = $serializer->serialize($response, 'json', [
    //     //     'circular_reference_handler' => function ($response) {
    //     //         return $response;
    //     //     }
    //     // ]);

    //     $response = new Response($json);
    //     $response->headers->set('Content-Type', 'application/json');
    //     return $response;
    // }
}
