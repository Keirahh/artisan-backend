<?php

namespace App\Controller;

use App\Repository\LocationCityRepository;
use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * @Route("/api/location")
 */
class LocationController extends ApiController
{
    private $locationRepository;
    private $locationCityRepository;

    public function __construct(LocationRepository $locationRepository, LocationCityRepository $locationCityRepository, SerializerInterface $serializer)
    {
        $this->locationRepository = $locationRepository;
        $this->locationCityRepository = $locationCityRepository;

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
     * @Route("/{name<\d+>?1}", name="get_locations", methods={"GET"})
     */
    public function getLocations(): Response
    {

        $city = $this->locationCityRepository->findOneBy(['name' => $_GET['name']]);
        return $this->serializeDoctrine($this->locationRepository->findBy(['city' => $city], array(), 10), 'location');
    }

    public function getEntity($id)
    {
        return $this->locationRepository->find($id);
    }
}
