<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->render('admin_lieu/index.html.twig', [
            'lieus' => $lieuRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_lieu_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $lieu = new Lieu();
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $imageFilename = $fileUploader->upload($imageFile);
            $lieu->setImage($imageFilename);
        }

            $entityManager->persist($lieu);
            $entityManager->flush();

            return $this->redirectToRoute('admin_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

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
        return $this->render('admin_lieu/show.html.twig', [
            'lieu' => $lieu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_lieu_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Lieu $lieu, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(LieuType::class, $lieu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
        if ($imageFile) {
            $imageFilename = $fileUploader->upload($imageFile);
            $lieu->setImage($imageFilename);
        }
            $entityManager->flush();

            return $this->redirectToRoute('admin_lieu_index', [], Response::HTTP_SEE_OTHER);
        }

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
        if ($this->isCsrfTokenValid('delete'.$lieu->getId(), $request->request->get('_token'))) {
            $entityManager->remove($lieu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_lieu_index', [], Response::HTTP_SEE_OTHER);
    }
}
