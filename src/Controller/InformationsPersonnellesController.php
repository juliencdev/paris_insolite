<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InformationsPersonnellesController extends AbstractController
{
    /**
     * @Route("/informations/personnelles", name="informations_personnelles")
     */
    public function index(): Response
    {
        return $this->render('informations_personnelles/index.html.twig', [
            'controller_name' => 'InformationsPersonnellesController',
        ]);
    }
}
