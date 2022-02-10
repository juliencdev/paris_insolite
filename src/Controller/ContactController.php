<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, MailerService $mailer): Response
    {   
        $formulaire=$this->createForm(ContactType::class);
        $formulaire->handleRequest($request);
        if ($formulaire->isSubmitted() && $formulaire->isValid()) {

            $data=$formulaire->getData();
            $email=$data['Mail'];
            $object="Coucou";
            $message=$data['Votre_demande'];
            // Mail to admin
            $mailer->sendEmail($email, "admin@gmail.com", $object, "emails/contact.html.twig", ['inputMessage'=>$message]);
            // Mail to user
            $mailer->sendEmail('admin@gmail.com', $email, "Thank you !", "emails/automatic.html.twig", ['mail' => $email]);
            return $this->render('contact/success.html.twig',[
                'email' => $email
            ]);
        }

        return $this->renderForm('contact/index.html.twig', [
            'contactAfficher' => $formulaire,
        ]);
    }
}
