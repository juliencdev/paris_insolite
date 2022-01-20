<?php
//Le groupe ou répertoire virtuel de la classe dans ce fichier
namespace App\Controller;
//On utilise AbstractController pour les méthodes qui sont héritées
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//Response permet de définir que les fonctions vont donner une réponse au navigateur
use Symfony\Component\HttpFoundation\Response;
//Route rend accessible une fonction par un navigateur
use Symfony\Component\Routing\Annotation\Route;
//La classe AccueilController hérite de la classe AbstractController
class AccueilController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(): Response
    {   
        // Affiche la vue 'accueil/index.html.twig'
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'Accueil',
        ]);
    }
}
