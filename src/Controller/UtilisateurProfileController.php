<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\Utilisateur2Type;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/utilisateur/profile")
 */
class UtilisateurProfileController extends AbstractController
{
    

    

    /**
     * @Route("/", name="utilisateur_profile_show", methods={"GET"})
     */
    public function show(): Response
    {
        $utilisateur=$this->getUser();
        return $this->render('profile/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    /**
     * @Route("/edit", name="utilisateur_profile_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur=$this->getUser();
        $form = $this->createForm(Utilisateur2Type::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('utilisateur_profile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

   
}
