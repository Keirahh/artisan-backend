<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;



class LocationController extends ApiController
{
    private $locationRepository;

    public function __construct(LocationRepository $locationRepository, SerializerInterface $serializer)
    {
        $this->locationRepository = $locationRepository;
        parent::__construct($serializer);
    }

    /**
     * @Route("/location/{id}", name="get_location", methods={"GET"})
     */
    public function getLocation($id): Response
    {
        return $this->serializeDoctrine($this->locationRepository->find($id),'location');
    }

    /**
     * @Route("/locations", name="get_locations", methods={"GET"})
     */
    public function getLocations(): Response
    {
        return $this->serializeDoctrine($this->locationRepository->findAll(),'location');
    }

    public function getEntity($id)
    {
        return $this->locationRepository->find($id);
    }

}
