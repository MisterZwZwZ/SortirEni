<?php

namespace App\Controller;

use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("{/profil/", name="profil_")
 */
class ProfilController extends AbstractController
{
    /**
     * @Route("{id}/afficher", name="afficher", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function afficherProfil(): Response
    {
        return $this->render('monprofil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    /**
     * @Route("modifier", name="modifier", methods={"GET", "POST"})
     */
    public function modifierProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $pass = $user->getPassword();
        dump($pass);
        $form = $this->createForm(ProfilType::class,$user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Modification enregistrée');
            return $this->redirectToRoute('profil_modifier', ['id' => $user->getId()]);
        }
        return $this->render('profil/monprofil.html.twig', [
            "userForm" => $form->createView(),
            'user' => $user
        ]);
    }
}
