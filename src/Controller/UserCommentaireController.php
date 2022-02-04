<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Lieu;
use App\Form\Commentaire1Type;
use App\Repository\CommentaireRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/commentaire")
 */
class UserCommentaireController extends AbstractController
{
     /**
     * @Route("/", name="user_commentaire_index", methods={"GET"})
     */
    public function index(CommentaireRepository $commentaireRepository): Response
    {
        $ensemble=$commentaireRepository->findBy(['auteur'=>$this->getUser()]);
        $nombre=count($ensemble);
        return $this->render('user_commentaire/index.html.twig', [
            'commentaires' => $ensemble,
            'nombrecom' => $nombre
        ]);
    }

    /**
     * @Route("/new/{id}", name="user_commentaire_new", methods={"GET", "POST"})
     */
    public function new(Lieu $lieu, Request $request, EntityManagerInterface $entityManager): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(Commentaire1Type::class, $commentaire);
        $form->handleRequest($request);
        $commentaire->setAuteur($this->getUser());
        $commentaire->setLieu($lieu);
        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setCreation(new DateTime());
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('catalog_show', [
                'id'=>$commentaire->getLieu()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_commentaire_show", methods={"GET"})
     */
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('user_commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_commentaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $utilisateurConnect=$this->getUser();
        if($utilisateurConnect!=$commentaire->getAuteur()){
            return $this->redirectToRoute('catalog_index');
        }
        $form = $this->createForm(Commentaire1Type::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_commentaire/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_commentaire_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        $utilisateurConnect=$this->getUser();
        if($utilisateurConnect!=$commentaire->getAuteur()){
            return $this->redirectToRoute('catalog_index');
        }
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_commentaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
