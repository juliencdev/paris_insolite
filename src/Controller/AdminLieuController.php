<?php
//Le groupe ou répertoire virtuel de la classe dans ce fichier
namespace App\Controller;
//On utilise les fonctions de la classe Lieu
use App\Entity\Lieu;
//On utilise le modèle de formulaire LieuType
use App\Form\LieuType;
//On utilise LieuRepository pour récupérer le détail des lieux
use App\Repository\LieuRepository;
//On utilise FileUploader pour l'upload d'images
use App\Service\FileUploader;
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
 * @Route("/admin/lieu")
 */
class AdminLieuController extends AbstractController
{
    /**
     * @Route("/", name="admin_lieu_index", methods={"GET"})
     */
    public function index(LieuRepository $lieuRepository): Response
    {   
        // affiche la vue'admin_lieu/index.html.twig' avec une variable TWIG 'lieus'
        //qui pointe vers la liste de tous les commentaires en base de données
        return $this->render('admin_lieu/index.html.twig', [
            'lieus' => $lieuRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_lieu_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        //on instancie en mémoire un espace qui a la structure de la classe Lieu et qui est vide
        $lieu = new Lieu();
        //on crée un formulaire $form à partir du modèle LieuType et il devra remplir $lieu si des données ont été envoyées
        $form = $this->createForm(LieuType::class, $lieu);
        // on dit au $form d'essayer de récupérer les données saisis par un visiteur dans $request
        $form->handleRequest($request);
        //si le formulaire trouve des données dans $request et si les données ont l'air valides
        if ($form->isSubmitted() && $form->isValid()) {
            //On peut alors uploader une image qui sera associée à un lieu
            $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $imageFilename = $fileUploader->upload($imageFile);
            $lieu->setImage($imageFilename);
        }
            //on demande à l'objet $entityManager de sauvegarder $lieu en base de données
            $entityManager->persist($lieu);
            //on demande à l'$entityManager de valider toutes les décisions avant, on valide la demande une fois pour toutes
            $entityManager->flush();
            //on redirige le navigateur vers la page de la liste de tous les lieux (nb:fct s'arrete car il y a un return)
            return $this->redirectToRoute('admin_lieu_index', [], Response::HTTP_SEE_OTHER);
        }
        //on affiche la vue avec deux variables twig;'lieu' et 'form', qui pointent en PHP vers $lieu et $form
        return $this->renderForm('admin_lieu/new.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_lieu_show", methods={"GET"})
     */
    public function show(Lieu $lieu): Response
    {
        //on retourne la vue 'admin_lieu/show.html.twig' avec comme var twig 'lieu' qui pointe en PHP vers $lieu
        return $this->render('admin_lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_lieu_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Lieu $lieu, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        //création du formulaire $form a partir du modele LieuType pour remplir $lieu
        $form = $this->createForm(LieuType::class, $lieu);
        //on essaie de récupérer les données saisies par le visiteur dans $request
        $form->handleRequest($request);
        //le formulaire regarde si des données ont été soumises et si elles ont l'air valides
        if ($form->isSubmitted() && $form->isValid()) {
        //on regarde si une image a été uploadée
            $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $imageFilename = $fileUploader->upload($imageFile);
            $lieu->setImage($imageFilename);
        }
        //on valide les changements une fois pour toutes
            $entityManager->flush();
        //on redirige vers la route interne 'admin_lieu_index'
            return $this->redirectToRoute('admin_lieu_index', [], Response::HTTP_SEE_OTHER);
        }
        //on affiche la vue 'admin_lieu/edit.html.twig' avec les variables twig qui pointent vers $lieu et $ form
        return $this->renderForm('admin_lieu/edit.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_lieu_delete", methods={"POST"})
     */
    public function delete(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        //On fait des vérifications de sécurité csrf pour éviter des usurpations d'identité
        if ($this->isCsrfTokenValid('delete'.$lieu->getId(), $request->request->get('_token'))) {
            //on demande à EntityManager de supprimer le lieu
            $entityManager->remove($lieu);
            $entityManager->flush();
        }
        //on redirige le navigateur vers la route de nom interne'admin_lieu_index'
        return $this->redirectToRoute('admin_lieu_index', [], Response::HTTP_SEE_OTHER);
    }
}
