<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class LocationController extends ApiController
{
    private $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @Route("/location/add/", name="add_location", methods={"POST"})
     */
    public function addLocation(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);


        $dataset = ['city', 'zip_code'];

        foreach ($dataset as $property) {
            if (empty($data[$property])) {
                throw new NotFoundHttpException('Expecting mandatory parameters! (' . $data[$property] . ')');
            }
        }

        $city = $data['city'];
        $zip_code = $data['zip_code'];

        // if (empty($city) || empty($zip_code)) {
        //     throw new NotFoundHttpException('Expecting mandatory parameters!');
        // }

        if ($this->locationRepository->saveLocation($city, $zip_code,)) {
            return new JsonResponse(['status' => 'Location created!'], Response::HTTP_CREATED);
        }
    }

    /**
     * @Route("/location/{id}", name="get_location", methods={"GET"})
     */
    public function getLocation($id): Response
    {
        return $this->serializeDoctrine($this->locationRepository->find($id),'location');
    }

    public function getEntity($id)
    {
        return $this->locationRepository->find($id);
    }
}
