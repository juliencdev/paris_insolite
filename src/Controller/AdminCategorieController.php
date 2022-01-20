<?php
//Le groupe ou répertoire virtuel de la classe dans ce fichier
namespace App\Controller;
//On utilise les fonctions de la classe Categorie
use App\Entity\Categorie;
//On utilise le modèle de formulaire CategorieType
use App\Form\CategorieType;
//On utilise CategorieRepository pour récupérer le détail des catégories
use App\Repository\CategorieRepository;
//on utilise EntityManager pour ajouter, modifier ou supprimer une catégorie
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
 * @Route("/admin/categorie")
 */
class AdminCategorieController extends AbstractController
{
    /**
     * @Route("/", name="admin_categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {   
        // affiche la vue'admin_categorie/index.html.twig' avec une variable TWIG 'categories'
        //qui pointe vers la liste de toutes les catégories en base de données
        return $this->render('admin_categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_categorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //on instancie en mémoire un espace qui a la structure de la classe Categorie et qui est vide
        $categorie = new Categorie();
        //on crée un formulaire $form à partir du modèle CategorieType et il devra remplir $categorie si des données ont été envoyées
        $form = $this->createForm(CategorieType::class, $categorie);
         // on dit au $form d'essayer de récupérer les données saisis par un visiteur dans $request
        $form->handleRequest($request);
        //si le formulaire trouve des données dans $request et si les données ont l'air valides
        if ($form->isSubmitted() && $form->isValid()) {
            //on demande à l'objet $entityManager de sauvegarder $categorie en base de données
            $entityManager->persist($categorie);
            //on demande à l'$entityManager de valider toutes les décisions avant, on valide la demande une fois pour toutes
            $entityManager->flush();
            //on redirige le navigateur vers la page de la liste de toutes les catégories (nb:fct s'arrete car il y a un return)
            return $this->redirectToRoute('admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }
        //on affiche la vue avec deux variables twig;'categorie' et 'form', qui pointent en PHP vers $categorie et $form
        return $this->renderForm('admin_categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {   
        //on retourne la vue 'admin_categorie/show.html.twig' avec comme var twig 'categorie' qui pointe en PHP vers $categorie
        return $this->render('admin_categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_categorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        //création du formulaire $form a partir du modele CategorieType pour remplir $categorie
        $form = $this->createForm(CategorieType::class, $categorie);
        //on essaie de récupérer les données saisies par le visiteur dans $request
        $form->handleRequest($request);
        //le formulaire regarde si des données ont été soumises et si elles ont l'air valides
        if ($form->isSubmitted() && $form->isValid()) {
            //on valide les changements une fois pour toutes
            $entityManager->flush();
            //on redirige vers la route interne 'admin_categorie_index'
            return $this->redirectToRoute('admin_categorie_index', [], Response::HTTP_SEE_OTHER);
        }
        //on affiche la vue 'admin_categorie/edit.html.twig' avec les variables twig qui pointent vers $categorie et $ form
        return $this->renderForm('admin_categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        //On fait des vérifications de sécurité csrf pour éviter des usurpations d'identité
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            //on demande à EntityManager de supprimer la catégorie
            $entityManager->remove($categorie);
            $entityManager->flush();
        }
        //on redirige le navigateur vers la route de nom interne'admin_categorie_index'
        return $this->redirectToRoute('admin_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}
