<?php

namespace App\Controller;

use App\Form\SelctionType;
use App\Repository\OeuvreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home.index")
     */
    public function index(OeuvreRepository $oeuvreRepository):Response
    {

        $oeuvres = $oeuvreRepository->getFourOeuvreRandom()->getResult();



        return $this->render('home/index.html.twig', [
            'oeuvres' => $oeuvres,
        ]);
    }
}