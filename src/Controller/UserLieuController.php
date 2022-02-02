<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\Lieu1Type;
use App\Repository\LieuRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/lieu")
 */
class UserLieuController extends AbstractController
{
    /**
     * @Route("/", name="user_lieu_index", methods={"GET"})
     */
    public function index(LieuRepository $lieuRepository): Response
    {
        return $this->render('user_lieu/index.html.twig', [
            'lieus' => $lieuRepository->findBy(['auteur'=>$this->getUser()]),
        ]);
    }

    /**
     * @Route("/new", name="user_lieu_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(Lieu1Type::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lieu->setAuteur($this->getUser());
            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $imageFilename = $fileUploader->upload($imageFile);
                $lieu->setImage($imageFilename);
            }
            $entityManager->persist($lieu);
            $entityManager->flush();

            return $this->redirectToRoute('user_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_lieu/new.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_lieu_show", methods={"GET"})
     */
    public function show(Lieu $lieu): Response
    {
        return $this->render('user_lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_lieu_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Lieu1Type::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('user_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_lieu/edit.html.twig', [
            'lieu' => $lieu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_lieu_delete", methods={"POST"})
     */
    public function delete(Request $request, Lieu $lieu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lieu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lieu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_lieu_index', [], Response::HTTP_SEE_OTHER);
    }
}
