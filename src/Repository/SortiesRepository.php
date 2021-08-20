<?php

namespace App\Repository;

use App\Entity\Campus;
use App\Entity\Sorties;
use App\Entity\User;
use DateTime;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @method Sorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorties[]    findAll()
 * @method Sorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortiesRepository extends ServiceEntityRepository
{
    protected ?Security $security = null;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sorties::class);
        $this->security = $security;
    }

    public function findBySelect(FormInterface      $form, ?User $user,
                                 ?DateTimeInterface $dateSortie,?DateTimeInterface $dateCloture,
                                 ?string            $keySearch, ?Campus $campus
    ){

        //récupération des données
        $booleanOrganisateur = $form->get('SortiesOrganisateurs')->getData()   ;
        $booleanUserInscrit = $form->get('SortiesInscrits')->getData()  ;
        $booleanUserNonInscrit = $form->get('SortiesNonInscrits')->getData()   ;
        $booleanSortiesPassees = $form->get('SortiesPassees')->getData()    ;

//        dump($booleanOrganisateur);
//        dump($booleanUserInscrit);
//        dump($booleanUserNonInscrit);
//        dump($booleanSortiesPassees);
//        exit();


        //Création de la requête de base
             $query = $this->createQueryBuilder('sorties')
                 ->orderBy('sorties.dateLimiteInscription', 'DESC');

             if($dateSortie !== null){
                 $query =
                     $query->andWhere(':dateSortieSaisie < sorties.dateLimiteInscription')
                     ->setParameter('dateSortieSaisie',$dateSortie );
             };
            if($dateCloture !== null){
                $query =
                    $query->andWhere('sorties.dateLimiteInscription < :dateClotureSaisie')
                    ->setParameter('dateSortieSaisie', $dateCloture);
            };
            if($keySearch !== null){
                $query =
                    $query->andWhere('sorties.nom LIKE :KeySearch')
                        ->setParameter('KeySearch','%'.$keySearch.'%');
            };
            if($campus !== null){
                $query =
                    $query->andWhere('sorties.siteOrganisateur = :campus')
                ->setParameter('campus', $campus);
            }
             //user est l'organisateur
            if ($booleanOrganisateur) {
                $query =
                    $query->andWhere('sorties.organisateur = :idUser')
                    ->setParameter('idUser', $user->getId());
//                dump($query->getQuery()->getResult());
//                exit();
            }

            //sorties auxquelles l'user est inscrit
            if ($booleanUserInscrit && ($booleanUserInscrit !== $booleanUserNonInscrit)) {
//                $query = $this->createQueryBuilder('sorties')
                $query = $query->innerJoin('sorties.listeDesInscrits', 'li', 'WITH',
                    'li = :user')
                    ->setParameter('user', $user);

            }

            //sorties auxquelles l'user n'est pas inscrit
            if ($booleanUserNonInscrit && ($booleanUserInscrit !== $booleanUserNonInscrit)) {
                $queryIns = $this->createQueryBuilder('sortiesIns')
                    ->innerJoin('sortiesIns.listeDesInscrits', 'li', 'WITH',
                        'li = :user')
                    ->setParameter('user', $user)
                    ->getQuery()->getResult();

                $query = $query->innerJoin('sorties.listeDesInscrits', 'li', 'WITH',
                    'li NOT IN (:sorties_user_inscrit)')
                    ->setParameter('sorties_user_inscrit', $queryIns);

            }
            if ($booleanSortiesPassees) {
                //sorties passées
                $query =
                    $query->andWhere(':dateNow > sorties.dateLimiteInscription')
                        ->setParameter('dateNow', new DateTime());
            }else{
                //sorties actuelles
                $query =
                    $query->andWhere(':dateNow < sorties.dateLimiteInscription')
                        ->setParameter('dateNow', new DateTime());

//                dump($query->getQuery()->getResult());
//                exit();
            }

        return $query->getQuery()->getResult();

        }
  
}
