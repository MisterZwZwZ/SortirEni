<?php

namespace App\Controller;

use App\Entity\Sorties;
use App\Entity\User;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/default/", name="default_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("", name="accueil", methods={"GET","POST"})
     */
    public function accueil(Request $request,EntityManagerInterface $entityManager): Response
    {

        //création du formulaire
        $formSortie = $this->createForm('App\Form\RechercheSortiesType', null);

        //récupération des données envoyées par le navigateur et les transmet au formulaire
        $formSortie->handleRequest($request);
        $listeSorties=[];

        //récupération des données
        $sorties = new Sorties();
//TODO Tester quand non connecté
        /** @var User $user */
        $user = $this->getUser();
        $dateSortie = $formSortie->get('dateHeureDebutRecherche')->getData();
        $dateCloture =$formSortie->get('dateFinRecherche')->getData();
        $keySearch =($formSortie->get('nomRecherche')->getData() === null?'':$formSortie->get('nomRecherche')->getData());
        $Campus =$formSortie->get('campus')->getData();

        //si l'utilisateur est connecté
        if ($user->getId()) {
            //Vérification des données du formulaires
            if ($formSortie->isSubmitted() && $formSortie->isValid()) {

                //demande table sortie en fonction des selecteurs
                $listeSorties = $entityManager->getRepository(Sorties::class)
                    ->findBySelect($formSortie, $user,$dateSortie , $dateCloture,$keySearch, $Campus);
            }
            else{
                $listeSorties = $entityManager->getRepository(Sorties::class)
                    ->findBySelect($formSortie, $user,$dateSortie , $dateCloture,$keySearch, $Campus);

            }
        }

        return $this->render('default/accueil.html.twig',[
            'form_accueil'=>$formSortie->createView(),
            'listeSorties'=>$listeSorties,
    ]);
    }

    /**
     * @return Response
     * @Route(path="inscription", name="inscription")
     */
    public function incription():Response
    {
        //TODO faire la méthode d'inscription dès réalisation des relations
        return $this->redirectToRoute('default_accueil');
    }

    /**
     * @Route(path="desinscription", name="desinscription")
     * @return Response
     */
    public function desinscription():Response
    {
        //TODO faire la méthode d'inscription dès réalisation des relations
        return $this->render('default/accueil.html.twig');
    }



}
