<?php

namespace App\Controller;

use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sorties;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/sortie/", name ="sortie_", requirements={"page"="\d+"})
 */
class SortieController extends AbstractController
{
    /**
     * @Route(path="new", name="new")
     * @Route(path="{id}/edit", name="edit")
     */
    public function form(Sorties $sortie = null,Request $request, EntityManagerInterface $entityManager)
    {
        if (!$sortie){
            $sortie = new Sorties();
        }

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie a été créer par succès');

            return $this->redirectToRoute('sortie_show', ['id' => $sortie.getIdSortie()]);
        }

        return $this->render('sortie/creerUneSortie.html.twig', [
            "sortieForm" => $sortieForm->createView()
        ]);
    }
    /**
     * @Route(path="show", name="show")
     */
    public function show(EntityManagerInterface $entityManager)
    {
        return $this->render('sortie/afficherUneSortie.html.twig');
    }

}