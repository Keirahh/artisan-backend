<?php

namespace App\Controller;

use Twig\Environment;
use App\Controller\RoleController;
use App\Controller\LocationController;
use App\csv\ImportCsv;
use App\imageImport\ImportImage;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $roleController;
    private $manager;
    private $locationRepository;

    public function __construct($twig, RoleController $rolecontroller, LocationRepository $locationRepository, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->roleController = $rolecontroller;
        $this->locationRepository = $locationRepository;
    }

    public function index()
    {
        $index = "Bonjour";
        // if (isset($_POST)) {
        //     $import = new ImportCsv($this->manager);

        //     return $this->render("pages/home.html.twig", array(
        //         "title" => $index,
        //         "result" => $import->importCsv(),
        //         "Location" =>$this->locationRepository->searchLocation('nic')
        //     ));
        // } else {
            return $this->render("pages/home.html.twig", array(
                "title" => $index,
                "role" => json_decode($this->roleController->getRole(2)->getContent()),
            ));
        // }
    }
}


