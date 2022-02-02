<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogCategorieController extends AbstractController
{
    /**
     * @Route("/catalog/categorie", name="catalog_categorie")
     */
    public function index(): Response
    {
        return $this->render('catalog_categorie/index.html.twig', [
            'controller_name' => 'CatalogCategorieController',
        ]);
    }
}
