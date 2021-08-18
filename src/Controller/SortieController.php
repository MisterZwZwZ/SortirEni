<?php

namespace App\Controller;

use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sorties;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/sortie/", name ="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route(path="create", name="create", methods={"GET", "POST"})
     */
    public function CreerUneSortie(Request $request, EntityManagerInterface $entityManager)
    {
        // Création de l'entité à créer
        $sortie = new Sorties();

        // Création du formulaire
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        // Récupérer les donnés du navigateur
        $sortieForm->handleRequest($request);

        // Vérifier les donnés du formulaire
        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){

            // Enregistrer les donnés dans la BDD
            $entityManager->persist($sortie);
            $entityManager->flush();

            // Ajout message de confirmation
            $this->addFlash('success', 'La sortie a été créer par succès');

            //Redirection sur le controlleur
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('sortie/creerUneSortie.html.twig', [
            "sortieForm" => $sortieForm->createView()
        ]);
    }
    /**
     * @Route(path="AfficherUneSortie", name="afficher_une_sortie")
     */
    public function AfficherUneSortie(EntityManagerInterface $entityManager)
    {
        return $this->render('sortie/afficherUneSortie.html.twig');
    }
    /**
     * @Route(path="ModifierUneSortie", name="modifier_une_sortie")
     */
    public function ModifierUneSortie()
    {
        return $this->render('sortie/modifierUneSortie.html.twig');
    }
}