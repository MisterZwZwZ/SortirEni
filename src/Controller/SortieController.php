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
        //Variable pour détecter le mode edit
        $edit = false;

        //Test et ajout de champs selon la route new ou edit
        if (!$sortie) {
            $sortie = new Sorties();
            $title = 'Créer une sortie';
            $btnPublier = 1;
        } else {
            $edit = true;
            $title = 'Modifier une sortie';
            if ($sortie->getEtatSortie()->getId() === 1){
                $btnPublier = 1;
            }elseif($sortie->getEtatSortie()->getId()){
                $btnPublier = 0;
            }
        }

        //Ajout du site oragnisateur et de l'organisateur
        $user = $this->getUser();
        $sortie->setOrganisateur($user);
        $sortie->setSiteOrganisateur($user->getCampusUser());

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        //Récupération des données des bouttons
        $btnSubmit = $request->get('btn-submit');

        //Si le boutton créer est submit il y a une redirection vers la page d'accueil et l'ajout de sortie avec etat créée
        if ($btnSubmit === "creer") {
            $etatCreer = $entityManager->getRepository('App:Etats')->find(1);
            $sortie->setEtatSortie($etatCreer);
            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success', 'La sortie a été créer avec succès');

                return $this->redirectToRoute('default_accueil');
            }

            //Si le boutton publier est submit il y a une redirection vers la page d'accueil et l'ajout de sortie avec etat publier
        } elseif ($btnSubmit === "publier") {
            $etatPublier = $entityManager->getRepository('App:Etats')->find(2);
            $sortie->setEtatSortie($etatPublier);
            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success', 'La sortie a été publier avec succès');

                return $this->redirectToRoute('default_accueil');
            }

            //Si le boutton annuler est submit il y a une redirection vers la page d'accueil
        } elseif ($btnSubmit === "annuler") {
            return $this->redirectToRoute('default_accueil');
        }

        if ($edit = true){
            return $this->render('sortie/gestionUneSortie.html.twig', [
                'sortieForm' => $sortieForm->createView(),
                'title' => $title,
                'sortie' => $sortie,
                'btnPublier' => $btnPublier
            ]);
        }else{
            return $this->render('sortie/gestionUneSortie.html.twig', [
                'sortieForm' => $sortieForm->createView(),
                'title' => $title,
                'btnPublier' => $btnPublier
            ]);
        }


    }

    /**
     * @Route(path="{id}/show", name="show")
     */
    public function show(Sorties $sortie, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        $campus = $sortie->getSiteOrganisateur();
        $lieu = $sortie->getLieu();
        $ville = $lieu->getVille();

        return $this->render('sortie/afficherUneSortie.html.twig', [
            "sortie" => $sortie,
            "campus" => $campus,
            "lieu" => $lieu,
            "ville" => $ville,
            "user" => $user
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
        if($sorties->getEtatSortie()->getId() == 2 || $sorties->getEtatSortie()->getId() == 3 )
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