<?php

namespace App\Controller;

use App\Repository\LocationCityRepository;
use App\Repository\LocationRepository;
use App\Repository\LocationZipRepository;
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
    private $locationCityRepository;
    private $locationZipRepository;

    public function __construct(LocationRepository $locationRepository, LocationCityRepository $locationCityRepository, LocationZipRepository $locationZipRepository, SerializerInterface $serializer)
    {
        $this->locationRepository = $locationRepository;
        $this->locationCityRepository = $locationCityRepository;
        $this->locationZipRepository = $locationZipRepository;

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
     * @Route("/{q<\d+>?1}", name="get_locations", methods={"GET"})
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

            $city = $this->locationCityRepository->findOneBy(['name' => $_GET['q']]);
            $zip = $this->locationZipRepository->findOneBy(['zip' => $_GET['q']]);
            // $rst = $this->locationRepository->findBy(['city' => $city], array(),10,200);

            $rst = $this->serializeDoctrine($this->locationRepository->findBy(['city' => $city], null, 10, null), 'city');
            $rst .= $this->serializeDoctrine($this->locationRepository->findBy(['zip' => $zip], array(), 10, null), 'zip');
            return new Response($rst, 200, [
                'Content-Type' => 'application/json'
            ]);
        }
    }

    public function getEntity($id)
    {
        return $this->locationRepository->find($id);
    }
}
