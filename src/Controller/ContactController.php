<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function index(Request $request): Response
    {
        $success = null;

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $success = "Merci de m'avoir contacté. Je vous répondrai dans les meilleurs délais.";
            
            $mail = new Mail();
            $content = "Bonjour ".$form->get("firstname")->getData()." ".$form->get("lastname")->getData().".<br>
            Merci de m'avoir contacté. Je vous répondrai dans les meilleurs délais.";
            $mail->send($form->get("email")->getData(), $form->get("firstname")->getData()." ".$form->get("lastname")->getData(), "Votre demande de contact a bien été prise en compte", $content);

            $mail = new Mail();
            $content = "Bonjour.<br>
            Vous avez reçu un mail de la part de ".$form->get("firstname")->getData()." ".$form->get("lastname")->getData()."<br>".$form->get("email")->getData()."<br>".$form->get("content")->getData();
            $mail->send("contact@romaincurtet.com", "Contact Romain CURTET", "Vous avez reçu une nouvelle demande de contact", $content);

        }

            
        return $this->render("contact/index.html.twig", [
            "form" => $form->createView(),
            "success" => $success,
        ]);
    }
}

