<?php

namespace App\Controller;

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
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    /**
     * @Route("{id}/modifier", name="modifier", requirements={"id": "\d+"}, methods={"GET", "POST"})
     */
    public function modifierProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }
}
