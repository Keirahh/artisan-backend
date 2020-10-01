<?php

namespace App\Controller;

use Twig\Environment;
// include '../csv/importCsv.php';
use App\Controller\RoleController;
use App\Controller\UserController;
use App\Controller\LocationController;
use App\csv\Import;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $roleController;
    private $manager;
    private $locationController;

    public function __construct($twig, RoleController $rolecontroller, UserController $usercontroller, LocationController $locationController, EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        $this->roleController = $rolecontroller;
        $this->userController = $usercontroller;
        $this->locationController = $locationController;
    }

    public function index()
    {
        $index = "Bonjour";
        if (isset($_POST)) {
            $import = new Import($this->manager);

            return $this->render("pages/home.html.twig", array(
                "title" => $index,
                "result" => $import->importCsv(),
                "Location" =>$this->locationController->displayFront()
            ));
        } else {
            return $this->render("pages/home.html.twig", array(
                "title" => $index,
                "role" => json_decode($this->roleController->getRole(2)->getContent()),
                "user" => json_decode($this->userController->getUser(1)->getContent()),
            ));
        }
    }
}
