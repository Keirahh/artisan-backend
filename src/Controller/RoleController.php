<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api/role")
 */
class RoleController extends ApiController
{
    /**
     * @var RoleRepository
     */
    private $roleRepository;

    /**
     * RoleController constructor.
     * @param RoleRepository $roleRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(RoleRepository $roleRepository, SerializerInterface $serializer)
    {
        $this->roleRepository = $roleRepository;
        parent::__construct($serializer);
    }

    /**
     * @Route("/add", name="add_role", methods={"POST"})
     */
    public function addRole(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        $dataset = ['name'];

        foreach ($dataset as $property) {
            if (empty($data[$property])) {
                throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$property] . ')');
            }
        }

        $name = $data['name'];

        if (empty($name)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        if ($this->roleRepository->saveRole($name)) {
            return new JsonResponse(['status' => 'Role created!'], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/{id}", name="get_role", methods={"GET"})
     */
    public function getRole($id): Response
    {
        return $this->serializeDoctrine($this->roleRepository->find($id), 'role');
    }

    /**
     * @Route("/roles", name="get_roles", methods={"GET"})
     */
    public function getRoles(): Response
    {
        return $this->serializeDoctrine($this->roleRepository->findAll(), 'roles');
    }

    public function getEntity($id)
    {
        return $this->roleRepository->find($id);
    }
}
