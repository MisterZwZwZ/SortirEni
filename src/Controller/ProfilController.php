<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profil/", name="profil_")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("afficher/{id}", name="afficher", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function afficherProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $profilId = $request->get('id');
        $profil = $entityManager->getRepository(User::class)->find($profilId);
        return $this->render('profil/afficherprofil.html.twig',['profil' => $profil]);
    }

    /**
     * @Route("modifier/{id}", requirements={"id": "\d+"}, name="modifier", methods={"GET", "POST"})
     */
    public function modifierProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ProfilType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Modification enregistrée');
            return $this->redirectToRoute('profil_modifier', ['id' => $user->getId()]);
        }
        // Reset User a ses valeurs par défaut pour qu'elles ne soient pas enregistrées en session en cas de non validation
        $entityManager->refresh($user);
        return $this->render('profil/monprofil.html.twig', [
            "userForm" => $form->createView(),
            'user' => $user
        ]);
    }
}
