<?php

namespace App\Controller;

use Twig\Environment;
use App\Controller\RoleController;
use App\Controller\LocationController;
use App\csv\ImportCsv;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{

    /**
     * @var \App\Controller\RoleController
     */
    private $roleController;
    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var LocationRepository
     */
    private $locationRepository;

    /**
     * HomeController constructor.
     * @param $twig
     * @param \App\Controller\RoleController $rolecontroller
     * @param LocationRepository $locationRepository
     * @param EntityManagerInterface $manager
     */
    public function __construct($twig, RoleController $rolecontroller, LocationRepository $locationRepository, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->roleController = $rolecontroller;
        $this->locationRepository = $locationRepository;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $index = "Bonjour";
        if (isset($_POST)) {
           // $importCsv = new ImportCsv($this->manager);
            return $this->render("pages/home.html.twig", array(
                "title" => $index,
                // "resultCsv" => $importCsv->importCsv(),
                "Location" => $this->locationRepository->searchLocation('nic')
            ));
        } else {
            return $this->render("pages/home.html.twig", array(
                "title" => $index,
                "role" => json_decode($this->roleController->getRole(2)->getContent()),
            ));
        }
    }
}
