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
     * HomeController constructor.
     * @param $twig
     */
    public function __construct($twig)
    {
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $index = "Bonjour";
            return $this->render("pages/home.html.twig", array(
                "title" => $index,
            ));
    }
}
