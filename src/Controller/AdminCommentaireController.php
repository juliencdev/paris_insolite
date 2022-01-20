<?php
//Le groupe ou répertoire virtuel de la classe dans ce fichier
namespace App\Controller;
//On utilise les fonctions de la classe Commentaire
use App\Entity\Commentaire;
//On utilise le modèle de formulaire CommentaireType
use App\Form\CommentaireType;
//On utilise CommentaireRepository pour récupérer le détail des commentaires
use App\Repository\CommentaireRepository;
//on utilise EntityManager pour ajouter, modifier ou supprimer des commentaires
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
 * @Route("/admin/commentaire")
 */
class AdminCommentaireController extends AbstractController
{
    /**
     * @Route("/", name="admin_commentaire_index", methods={"GET"})
     */
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        // affiche la vue'admin_commentaire/index.html.twig' avec une variable TWIG 'commentaires'
        //qui pointe vers la liste de tous les commentaires en base de données
        return $this->render('admin_commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_commentaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //on instancie en mémoire un espace qui a la structure de la classe Commentaire et qui est vide
        $commentaire = new Commentaire();
        //on crée un formulaire $form à partir du modèle CommentaireType et il devra remplir $commentaire si des données ont été envoyées
        $form = $this->createForm(CommentaireType::class, $commentaire);
        // on dit au $form d'essayer de récupérer les données saisis par un visiteur dans $request
        $form->handleRequest($request);
        //si le formulaire trouve des données dans $request et si les données ont l'air valides
        if ($form->isSubmitted() && $form->isValid()) {
            //on demande à l'objet $entityManager de sauvegarder $commentaire en base de données
            $entityManager->persist($commentaire);
            //on demande à l'$entityManager de valider toutes les décisions avant, on valide la demande une fois pour toutes
            $entityManager->flush();
            //on redirige le navigateur vers la page de la liste de tous les commentaires (nb:fct s'arrete car il y a un return)
            return $this->redirectToRoute('admin_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }
        //on affiche la vue avec deux variables twig;'commentaire' et 'form', qui pointent en PHP vers $commentaire et $form
        return $this->renderForm('admin_commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_commentaire_show", methods={"GET"})
     */
    public function show(Commentaire $commentaire): Response
    {
        //on retourne la vue 'admin_commentaire/show.html.twig' avec comme var twig 'commentaire' qui pointe en PHP vers $commentaire
        return $this->render('admin_commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        //création du formulaire $form a partir du modele CommentaireType pour remplir $commentaire
        $form = $this->createForm(CommentaireType::class, $commentaire);
        //on essaie de récupérer les données saisies par le visiteur dans $request
        $form->handleRequest($request);
        //le formulaire regarde si des données ont été soumises et si elles ont l'air valides
        if ($form->isSubmitted() && $form->isValid()) {
            //on valide les changements une fois pour toutes
            $entityManager->flush();
            //on redirige vers la route interne 'admin_commentaire_index'
            return $this->redirectToRoute('admin_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }
        //on affiche la vue 'admin_commentaire/edit.html.twig' avec les variables twig qui pointent vers $commentaire et $ form
        return $this->renderForm('admin_commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_commentaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        //On fait des vérifications de sécurité csrf pour éviter des usurpations d'identité
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            //on demande à EntityManager de supprimer le commentaire
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }
        //on redirige le navigateur vers la route de nom interne'admin_commentaire_index'
        return $this->redirectToRoute('admin_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
