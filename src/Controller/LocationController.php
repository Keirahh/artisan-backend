<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/api/location")
 */
class LocationController extends ApiController
{
    private $locationRepository;

    public function __construct(LocationRepository $locationRepository, SerializerInterface $serializer)
    {
        $this->locationRepository = $locationRepository;

        parent::__construct($serializer);
    }

    /**
     * @Route("/{id}", name="get_location", methods={"GET"})
     */
    public function getLocation($id): Response
    {
        return $this->serializeDoctrine($this->locationRepository->find($id), 'location');
    }

    /**
     * @Route("/{q<\d+>?1}/{page<\d+>?1}", name="get_locations", methods={"GET"})
     */
    public function getLocations(Request $request): Response
    {
        if (!$_GET) {
            $page = $request->query->get('page');
            if (is_null($page) || $page < 1) {
                $page = 1;
            }
            $limit = 10;
            return $this->serializeDoctrine($this->locationRepository->findAllLocations($page, $limit), 'location');
        } else {
            return $this->serializeDoctrine($this->locationRepository->searchLocation($_GET['q']), 'location');
        }
    }

    public function getEntity($id)
    {
        return $this->locationRepository->find($id);
    }
}
