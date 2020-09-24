<?php

namespace App\Controller;

use Twig\Environment;
use App\Controller\RoleController;
use App\Controller\UserController;
use App\Controller\LocationController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $roleController;

    public function __construct($twig, RoleController $rolecontroller, UserController $usercontroller, LocationController $locationcontroller)
    {
        $this->roleController = $rolecontroller;
        $this->userController = $usercontroller;
        $this->locationController = $locationcontroller;
    }

    public function index()
    {
        $index = "Bonjour";
        return $this->render("pages/home.html.twig", array(
            "title" => $index,
            "role" => json_decode($this->roleController->getRole(2)->getContent()),
            "user" => json_decode($this->userController->getUser(1)->getContent()),
            "location" => json_decode($this->locationController->getLocation(1)->getContent())

        ));
    }
}
