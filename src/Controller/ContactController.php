<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(): Response
    {   
        $formulaire=$this->createForm(ContactType::class);
        return $this->renderForm('contact/index.html.twig', [
            'contactAfficher' => $formulaire,
        ]);
    }
}
