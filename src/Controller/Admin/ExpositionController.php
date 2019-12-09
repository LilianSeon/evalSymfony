<?php

namespace App\Controller\Admin;

use App\Entity\Exposition;
use App\Form\ExpositionType;
use App\Repository\ExpositionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/expositions")
 */
class ExpositionController extends AbstractController
{
    /**
     * @Route("/", name="admin.exposition.index")
     */
    public function index(ExpositionRepository $expositionRepository):Response
    {
        $expositions = $expositionRepository->findAll();

        $today = new \DateTime();


        return $this->render('admin/exposition/index.html.twig', [
            'expositions' => $expositions,
            'today' => $today
        ]);
    }

    /**
     * @Route("/form", name="admin.exposition.form")
     * @Route("/form/update/{id}", name="admin.exposition.form.update")
     */
    public function form(Request $request, EntityManagerInterface $entityManager, int $id = null, ExpositionRepository $expositionRepository):Response
    {

        $model = $id ? $expositionRepository->find($id) : new Exposition();
        $type = ExpositionType::class;
        $form = $this->createForm($type, $model);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //dd($form->getData());

            // Message de confirmation
            $message = $model->getId() ? "L'exposition a été modifiée" : "L'exposition a été ajoutée";

            //,message stocké en session
            $this->addFlash('notice', $message);

            $model->getId() ? null : $entityManager->persist($model);
            $entityManager->flush();


            //redirection
            return $this->redirectToRoute('admin.exposition.index');
        }

        return $this->render('admin/exposition/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove/{id}", name="admin.exposition.remove")
     */
    public function remove(ExpositionRepository $expositionRepository, EntityManagerInterface $entityManager, int $id):Response
    {
        // selection de l'entité à supprimer
        $model = $expositionRepository->find($id);

        //suppression dans la table
        $entityManager->remove($model);
        $entityManager->flush();


        // message de redirection
        $this->addFlash('notice', "L'exposition a été supprimée");
        return $this->redirectToRoute('admin.exposition.index');
    }
}