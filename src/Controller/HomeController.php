<?php

namespace App\Controller;

use Twig\Environment;
use App\Controller\RoleController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $roleController;

    public function __construct($twig, RoleController $controller)
    {
        $this->roleController = $controller;
    }

    public function index()
    {
        $index = "Bonjour";
        return $this->render("pages/home.html.twig", array(
            "title" => $index,
            "toto" => json_decode($this->roleController->getRole(2)->getContent()),
            "titi" => json_decode($this->roleController->getRoles()->getContent())

        ));
    }
}
