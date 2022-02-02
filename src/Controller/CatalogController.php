<?php
//Le groupe ou répertoire virtuel de la classe dans ce fichier
namespace App\Controller;
//On utilise les fonctions de la classe catégorie
use App\Entity\Categorie;
//On utilise les fonctions de la classe lieu
use App\Entity\Lieu;
//On utilise le modèle de formulaire Lieu1Type
use App\Form\Lieu1Type;
//On utilise CommentaireRepository pour récupérer le détail des lieux
use App\Repository\LieuRepository;
//on utilise EntityManager pour ajouter, modifier ou supprimer des lieux
use Doctrine\ORM\EntityManagerInterface;
//On utilise AbstractController pour les méthodes qui sont héritées
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// on utilise Request pour trouver les infos saisies par l'utilisateur
use Symfony\Component\HttpFoundation\Request;
//Response permet de définir que les fonctions vont donner une réponse au navigateur
use Symfony\Component\HttpFoundation\Response;
//Route rend accessible une fonction par un navigateur
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
        $lieu = $lieuRepository->findAll();
        //On affiche la vue 'catalog/index.html.twig' avec une variable TWIG 'lieus' qui pointe vers $lieu c'est à dire tous 
        //les lieux qui sont en base de données
        return $this->render('catalog/index.html.twig', [
            'lieus' => $lieu,
            'categorie' => ['nom'=>'générale']
        ]);
    }
     /**
     * @Route("/categorie", name="categorie_index", methods={"GET"})
     */
    public function findCategorie(LieuRepository $lieuRepository): Response
    {
        $lieu = $lieuRepository->findBy();
        //On affiche la vue 'catalog/index.html.twig' avec une variable TWIG 'lieus' qui pointe vers $lieu c'est à dire tous 
        //les lieux qui sont en base de données
        return $this->render('catalog/index.html.twig', [
            'lieus' => $lieu,
            'categorie' => ['nom'=>'générale']
        ]);
    }
    

     /**
     * @Route("/categorie/{id}", name="catalog_category", methods={"GET"})
     */
    public function indexCategorie(Categorie $categorie): Response
    {
        
        //On récupère tous les lieux en relation avec la catégorie et on les met dans une variable $lieu
        $lieu = $categorie->getLieux();
        //On affiche la vue 'catalog/index.html.twig' avec une variable TWIG 'lieus' qui pointe vers $lieu 
        return $this->render('catalog/index.html.twig', [
            'lieus' => $lieu,
            'categorie' => $categorie
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
