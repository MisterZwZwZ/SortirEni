<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Sorties;
use App\Entity\User;
use App\Repository\SortiesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use function Faker\Provider\DateTime;
use function Sodium\add;

/**
 * @Route(path="/default/", name="default_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route(path="", name="accueil", methods={"GET","POST"})
     */
    public function accueil(Request $request,EntityManagerInterface $entityManager): Response
    {
//        //message flash test
//        $this->addFlash('success', 'coucou');

        //création du formulaire
        $formSortie = $this->createForm('App\Form\RechercheSortiesType', null);

        //récupération des données envoyées par le navigateur et les transmet au formulaire
        $formSortie->handleRequest($request);

        /** @var User $user */
        $user = $this->getUser();

        $dateDebRech = $formSortie->get('dateHeureDebutRecherche')->getData();
        $dateFinRech =$formSortie->get('dateFinRecherche')->getData();
        $keySearch =($formSortie->get('nomRecherche')->getData() === null?'':$formSortie->get('nomRecherche')->getData());
        $campus =$formSortie->get('campus')->getData();
        $booleanOrganisateur = $formSortie->get('SortiesOrganisateurs')->getData()   ;
        $booleanUserInscrit = $formSortie->get('SortiesInscrits')->getData()  ;
        $booleanUserNonInscrit = $formSortie->get('SortiesNonInscrits')->getData()   ;
        $booleanSortiesPassees = $formSortie->get('SortiesPassees')->getData()    ;


        $listeSorties = $entityManager->getRepository(Sorties::class)

            ->findBySelect($user, $dateDebRech, $dateFinRech,$keySearch, $campus, $booleanOrganisateur, $booleanUserInscrit, $booleanUserNonInscrit, $booleanSortiesPassees);

        // On vérifie si on a une requête Ajax
        if($request->get('ajax')){

            // On récupère les paramètres de la requête AJAX
            $params = $request->get('recherche_sorties'); //le tableau de paramètres

            // On va chercher l'objet Campus à partir de l'id récupéré en requête
            $idCampus = $params['campus'];
            $campus = $entityManager->getRepository(Campus::class) ->find($idCampus);

            $keySearch = $params['nomRecherche'];

            //Si le paramètre date est renseigné, on converti le Strings en date, sinon on l'initialise à null
            if($params['dateHeureDebutRecherche'] == ""){
                $dateDebRech = null;
            }else{
                $dateDebRechStr = $params['dateHeureDebutRecherche'];
                $dateDebRech = DateTime::createFromFormat('Y-m-d', $dateDebRechStr);
            }

            if($params['dateFinRecherche'] == ""){
                $dateFinRech = null;
            }else{
                $dateFinRechStr = $params['dateFinRecherche'];
                $dateFinRech = DateTime::createFromFormat('Y-m-d', $dateFinRechStr);
            }


            // On vérifie que l'on a récupéré le checkbox cochée, sinon on l'initialise à false (car elle ne sera pas envoyée en param dans ce cas)
            if( isset($params['SortiesOrganisateurs'])){
                $booleanOrganisateur=$params['SortiesOrganisateurs'];
            }else{
                $booleanOrganisateur=false;
            }

            if( isset($params['SortiesInscrits'])) {
                $booleanUserInscrit = $params['SortiesInscrits'];
            }else{
                $booleanUserInscrit = false;
            }

            if( isset($params['SortiesNonInscrits'])){
                $booleanUserNonInscrit=$params['SortiesNonInscrits'];
            }else{
                $booleanUserNonInscrit = false;
            }

            if( isset($params['SortiesPassees'])){
                $booleanSortiesPassees=$params['SortiesPassees'];
            }else{
                $booleanSortiesPassees = false;
            }

            // on lance la requête en BDD pour récupérer les données
            $listeSorties = $entityManager->getRepository(Sorties::class)
                ->findBySelect($user,
                    $dateDebRech,
                    $dateFinRech,
                    $keySearch,
                    $campus,
                    $booleanOrganisateur,
                    $booleanUserInscrit,
                    $booleanUserNonInscrit,
                    $booleanSortiesPassees);

            // On revoit la reponse au format JSON
            return new JsonResponse([
                'content' => $this->renderView(
                    'default/content.html.twig',
                    ['listeSorties' => $listeSorties]
                )
            ]);
        }

        return $this->render('default/accueil.html.twig',[
            'form_accueil'=>$formSortie->createView(),
            'listeSorties'=>$listeSorties,
    ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     * @Route(path="inscription", name="inscription", methods={"GET"})
     */
    public function incription(Request $request, EntityManagerInterface $manager):Response
    {

        //récup de l'id de sortie
        $idSorties = $request->get('id');

        //récup. de la sortie
        $sortie =$manager->getRepository('App:Sorties')->find($idSorties);

        //récupération de l'utilisateur
        /** @var User $user */
        $user = $this->getUser();

        //si état de la sortie "ouverte" et nombre d'inscrit < inscrit max
        if($sortie->getEtatSortie()->getLibelle() === "Ouverte" &&
            count($sortie->getListeDesInscrits()) < $sortie->getNbIncriptionsMax() &&
            new Datetime < $sortie->getDateLimiteInscription()
        ){

            $sortie->addListeDesInscrit($user);
            $manager->persist($sortie);

            $manager->flush();

        }else{
            $this->addFlash('warning', 'Désolé la sortie est complète ou l\'état de la sortie n\'est pas "ouverte"');
        }

        return $this->redirectToRoute('default_accueil');
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route(path="desinscription", name="desinscription", methods={"GET"})
     */
    public function desinscription(Request $request, EntityManagerInterface $entityManager):Response
    {
        //récup. l'id de la sortie
        $idSortie = $request->get('id');
        //récup. de la sortie
        $sortie = $entityManager->getRepository('App:Sorties')->find($idSortie);
        //récup. l'utilisateur
        /**@var User $user */
        $user = $this->getUser();

        //si la date actuelle est inférieure a la date de clôture de la sortie et que l'état de sortie est ouvert
        if(new DateTime() < $sortie->getDateLimiteInscription() &&
            $sortie->getEtatSortie()->getLibelle() === "Ouverte"){
            $sortie->removeListeDesInscrit($user);
            $entityManager->persist($sortie);
            $entityManager->flush();
        }else{
            $this->addFlash('warning', 'Il n\'est plus possible de vous désinscrire date de clôture dépassée"');
        }

        return $this->redirectToRoute('default_accueil');
    }

}
