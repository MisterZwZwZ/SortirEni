<?php

namespace App\Controller;

use App\Entity\Sorties;
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
        $sorties = new Sorties();

        //création du formulaire
        $formSortie = $this->createForm('App\Form\RechercheSortiesType',$sorties,);

        //récupération des données envoyées par le navigateur et les transmet au formulaire
        $formSortie->handleRequest($request);

        //Vérification des données du formulaires
        if($formSortie->isSubmitted() && $formSortie->isValid()){

            $listeSorties = $entityManager->getRepository(Sorties::class)->find(1);

        }else{
            //récupère toutes les sorties
            $listeSorties = $entityManager->getRepository(Sorties::class)->findAll();
//            dump($listeSorties);
//            exit();

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
