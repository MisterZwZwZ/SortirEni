<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\User;
use App\Form\AffichageSortieType;
use App\Form\SortieType;
use DateTimeImmutable;
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
     * @Route(path="new", name="new", methods={"GET","POST"})
     * @Route(path="{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function gestionSortie(Sorties $sortie = null,Request $request, EntityManagerInterface $entityManager)
    {
        if (!$sortie) {
            $sortie = new Sorties();
            $title = 'Créer une sortie';
        } else {
            $title = 'Modifier une sortie';
        }
        /**@var User $user*/
        $user = new User();
        $user = $this->getUser();
        $sortie->setSiteOrganisateur($user->getCampusUser());
        $sortie->setOrganisateur($user);

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        $btnSubmit = $request->get('btn-submit');

        if ($btnSubmit === "creer") {
            $sortie->setEtatSortie($entityManager->getRepository('App:Etats')->find(1));
            dump($sortie);
            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success', 'La sortie a été créer avec succès');

                return $this->redirectToRoute('default_accueil');
            }
        } elseif ($btnSubmit === "publier") {
            $sortie->setEtatSortie($entityManager->getRepository('App:Etats')->find(2));

            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success', 'La sortie a été publier avec succès');

                return $this->redirectToRoute('default_accueil');
            }
        } elseif ($btnSubmit === "annuler") {
            $id = $sortie->getIdSortie();
            return $this->redirectToRoute('sortie_show', ['id' => $id]);
        }

        return $this->render('sortie/gestionUneSortie.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'title' => $title
        ]);
    }

    /**
     * @Route(path="{id}/show", name="show")
     */
    public function show(Sorties $sortie, EntityManagerInterface $entityManager)
    {
        $campus = $sortie->getSiteOrganisateur();
        $lieu = $sortie->getLieu();
        $ville = $lieu->getVille();

        return $this->render('sortie/afficherUneSortie.html.twig', [
            "sortie" => $sortie,
            "campus" => $campus,
            "lieu" => $lieu,
            "ville" => $ville,
        ]);
    }

    /**
     * @Route(path="{id}/annuler" , name="annuler",methods={"GET","POST"})
     */
    public function annuler(Sorties $sorties,Request $request, EntityManagerInterface $entityManager)
    {

        $form = $this->createForm('App\Form\AnnulerSortieType',null);
        $form->handleRequest($request);
        //vérification que la sortie est publiée pour être annulée
        if($sorties->getEtatSortie()===2 || $sorties->getEtatSortie()===3 )
            if($form->isSubmitted() && $form->isValid()){
                $sorties->setEtatSortie($entityManager->getRepository('App:Etats')->find(6));

                //            dump($form->get('motif')->getData());
                //            exit();
                $motif = "Sortie ANNULEE - ".$form->get('motif')->getData();
                $sorties->setInfosSortie($motif);

                $entityManager->persist($sorties);
                $entityManager->flush();

                return $this->redirectToRoute('default_accueil');
            }
            else{
                $this->addFlash('warning', 'la sortie ne peut être annulée. veuillez la publier d\'abord');
            }

        return $this->render('sortie/annulerSortie.html.twig',
            ['sortie'=>$sorties, 'form'=>$form->createView()]
        );

    }
}