<?php

namespace App\Controller;

use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogArrondissementController extends AbstractController
{
    /**
     * @Route("/catalog/arrondissement", name="catalog_arrondissement")
     */
    
    public function index(Request $request, LieuRepository $lieuRepository): Response
    {   
       $search = $request->query->get('search');
      
       if ($search) {
           $lieu = $lieuRepository->findBy(["codePostal" =>$search]);
       } else {
           $lieu = $lieuRepository->findAll();
       }
       return $this->render('catalog_arrondissement/index.html.twig', [
        'lieus' => $lieu
       ]);
    }
}
