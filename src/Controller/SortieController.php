<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etats;
use App\Form\AffichageSortieType;
use App\Form\SortieType;
use App\Repository\EtatsRepository;
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
     * @Route(path="new", name="new")
     * @Route(path="{id}/edit", name="edit")
     */
    public function gestionSortie(Sorties $sortie = null,Request $request, EntityManagerInterface $entityManager)
    {
        if (!$sortie){
            $sortie = new Sorties();
            $title = 'Créer une sortie';
        } else{
            $title = 'Modifier une sortie';
            $dateDebut = (DateTimeImmutable::createFromMutable($sortie->getDateHeureDebut()));
            $dateInscr = (DateTimeImmutable::createFromMutable($sortie->getDateLimiteInscription()));
            $sortie->setDateHeureDebut($dateDebut);
            $sortie->setDateLimiteInscription($dateInscr);
        }

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'La sortie a été créer par succès');

            return $this->redirectToRoute('sortie_show');
        }

        return $this->render('sortie/creerUneSortie.html.twig', [
            "sortieForm" => $sortieForm->createView(),
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