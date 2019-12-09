<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// préfixe de toutes les routes contenues dans le contrôleur
/**
 * @Route("/admin")
 */
class HomeController extends AbstractController
{
    /**
    * @Route("/", name="admin.home.index")
     */
    public function index():Response
    {
        return $this->render('admin/index.html.twig');
    }

}