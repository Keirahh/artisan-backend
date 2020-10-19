<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;

/**
 * @Route("/api/location")
 */
class LocationController extends ApiController
{
    /**
     * @var LocationRepository
     */
    private $locationRepository;

    /**
     * LocationController constructor.
     * @param LocationRepository $locationRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(LocationRepository $locationRepository, SerializerInterface $serializer)
    {
        $this->locationRepository = $locationRepository;

        parent::__construct($serializer);
    }


    /**
     * Get location by query
     * 
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

    /**
     * Get location by Id 
     * 
     * It is necessary to have the identification token to be able to retrieve the ad
     * 
     * @Route("/{id}", name="get_location", methods={"GET"})
     */
    public function getLocation($id): Response
    {
        return $this->serializeDoctrine($this->locationRepository->find($id), 'location');
    }


    /**
     * @param $id
     * @return \App\Entity\Location|null
     */
    public function getEntity($id)
    {
        return $this->locationRepository->find($id);
    }
}
