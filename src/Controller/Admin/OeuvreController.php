<?php

namespace App\Controller\Admin;

use App\Entity\Exposition;
use App\Entity\Oeuvre;
use App\Form\ExpositionType;
use App\Form\OeuvreType;
use App\Repository\ExpositionRepository;
use App\Repository\OeuvreRepository;
use App\Services\FileService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/oeuvres")
 */
class OeuvreController extends AbstractController
{
    /**
     * @Route("/", name="admin.oeuvre.index")
     */
    public function index(OeuvreRepository $oeuvreRepository):Response
    {
        $oeuvres = $oeuvreRepository->findAll();



        return $this->render('admin/oeuvre/index.html.twig', [
            'oeuvres' => $oeuvres,
        ]);
    }

    /**
     * @Route("/form", name="admin.oeuvre.form")
     * @Route("/form/update/{id}", name="admin.oeuvre.form.update")
     */
    public function form(Request $request, EntityManagerInterface $entityManager, int $id = null, OeuvreRepository $oeuvreRepository):Response
    {
        // si l'id est nul, une insertion exécutée, sinon une modification est exécutée

        $model = $id ? $oeuvreRepository->find($id) : new Oeuvre();
        $type = OeuvreType::class;
        $form = $this->createForm($type, $model);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dd($form->getData());

            // Message de confirmation
            $message = $model->getId() ? "L'oeuvre' a été modifié" : "L'oeuvre' a été ajouté";

            //,message stocké en session
            $this->addFlash('notice', $message);

            $model->getId() ? null : $entityManager->persist($model);
            $entityManager->flush();


            //redirection
            return $this->redirectToRoute('admin.oeuvre.index');
        }

        return $this->render('admin/oeuvre/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="admin.oeuvre.remove")
     */
    public function remove(OeuvreRepository $oeuvreRepository, EntityManagerInterface $entityManager, int $id, FileService $fileService):Response
    {

        // selection de l'entité à supprimer
        $model = $oeuvreRepository->find($id);

        //suppression dans la table
        $entityManager->remove($model);
        $entityManager->flush();

        //suppression de l"image
        if(file_exists("img/oeuvre/{$model->getImage()}")) {
            $fileService->remove('img/oeuvre', $model->getImage());
        }

        // message de redirection
        $this->addFlash('notice', "L'oeuvre a été supprimé");
        return $this->redirectToRoute('admin.oeuvre.index');
    }
}