<?php

namespace App\Controller;

use App\Form\SelctionType;
use App\Repository\ArtisteRepository;
use App\Repository\CategoryRepository;
use App\Repository\OeuvreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OeuvreController extends AbstractController
{
    /**
     * @Route("/oeuvres", name="oeuvre.index")
     */
    public function index(OeuvreRepository $oeuvreRepository):Response
    {
        $oeuvres = $oeuvreRepository->findAll();
        $type = SelctionType::class;
        $form = $this->createForm($type);


        return $this->render('oeuvre/index.html.twig', [
            'oeuvres' => $oeuvres,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/oeuvres/filter", name="oeuvre.filter", methods={"POST"})
     */
    public function filter(Request $request, OeuvreRepository $oeuvreRepository):JsonResponse
    {
        //test d'une requête ajax
        if (!$request->isXmlHttpRequest()){
            return new JsonResponse([
                'message' => 'Unauthorized'
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        $formData = json_decode($request->getContent());


        $response = [
            'data' => $formData->category ?
                $oeuvreRepository->getOeuvreByCategoryId($formData->category)->getArrayResult() : $oeuvreRepository->getAllOeuvre()->getArrayResult()
        ];

        return new JsonResponse($response);

    }

    /**
     * @Route("/oeuvres/{id}", name="oeuvre.details")
     */
    public function details(OeuvreRepository $oeuvreRepository, int $id, CategoryRepository $categoryRepository, ArtisteRepository $artisteRepository):Response
    {
        $result = $oeuvreRepository->find($id);




        return $this->render('oeuvre/details.html.twig', [
            'result' => $result
        ]);
    }

}