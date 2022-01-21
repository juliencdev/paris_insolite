<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\Lieu1Type;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/catalog")
 */
class CatalogController extends AbstractController
{
    /**
     * @Route("/", name="catalog_index", methods={"GET"})
     */
    public function index(LieuRepository $lieuRepository): Response
    {
        return $this->render('catalog/index.html.twig', [
            'lieus' => $lieuRepository->findAll(),
        ]);
    }

    

    /**
     * @Route("/{id}", name="catalog_show", methods={"GET"})
     */
    public function show(Lieu $lieu): Response
    {
        return $this->render('catalog/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

   

  
}
