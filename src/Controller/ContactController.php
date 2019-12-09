<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Form\Model\ContactModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact.form")
     */
    public function form(Request $request, Environment $twig):Response
    {
        // affichage du formulaire
        $type = ContactType::class;
        $model = new ContactModel();

        $form = $this->createForm($type, $model);

        // handleRequest : récupération des données en $_POST
        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()){

            $firstname = $form->get('firstname')->getData();
            $lastname = $form->get('lastname')->getData();
            $email = $form->get('email')->getData();
            $message = $form->get('message')->getData();

            return $this->render('result/contact.html.twig', [
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'message' => $message
            ]);
        }

        return $this->render('contact/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}