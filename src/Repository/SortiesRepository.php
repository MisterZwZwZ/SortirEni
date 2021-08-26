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
use phpDocumentor\Reflection\Types\Boolean;
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


        /**
     * @param User|null $user
     * @param DateTimeInterface|null $dateDebRech
     * @param DateTimeInterface|null $dateFinRech
     * @param string|null $keySearch
     * @param Campus|null $campus
     * @param Bool|null $booleanOrganisateur
     * @param Bool|null $booleanUserInscrit
     * @param Bool|null $booleanUserNonInscrit
     * @param Bool|null $booleanSortiesPassees
     * @return int|mixed|string
     * Return les éléments en fonctions de la selection des checkboxs de la page d'accueil
     */



    public function findBySelect(?User              $user,
                                 ?DateTimeInterface $dateDebRech, ?DateTimeInterface $dateFinRech,
                                 ?string            $keySearch, ?Campus $campus,
                                 ?Bool              $booleanOrganisateur, ?Bool $booleanUserInscrit,
                                 ?Bool              $booleanUserNonInscrit, ?Bool $booleanSortiesPassees
    ){


//        //Création de la requête de base
//             $query = $this->createQueryBuilder('sorties')
//                 ->orderBy('sorties.dateLimiteInscription', 'DESC');

        //Création de la requête de base
        $query = $this->createQueryBuilder('sorties')
            ->andwhere('sorties.etatSortie != 1 OR (sorties.etatSortie = 1 AND sorties.organisateur = :userId)')
            ->setParameter('userId', $user->getId())
            ->orderBy('sorties.dateLimiteInscription', 'DESC');


             if($dateDebRech !== null){
                 $query =
                     $query->andWhere(':dateDebRecherche < sorties.dateLimiteInscription')
                     ->setParameter('dateDebRecherche',$dateDebRech );
             };

            if($dateFinRech !== null){
                $query =
                    $query->andWhere('sorties.dateLimiteInscription < :dateFinRecherche')
                    ->setParameter('dateFinRecherche', $dateFinRech);
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
                $query = $query->innerJoin('sorties.listeDesInscrits', 'li', 'WITH',
                    'li = :user')
                    ->setParameter('user', $user);

            }

            //sorties auxquelles l'user n'est pas inscrit
            if ($booleanUserNonInscrit && ($booleanUserInscrit !== $booleanUserNonInscrit)) {
//                $queryIns = $this->createQueryBuilder('sortiesIns')
//                    ->innerJoin('sortiesIns.listeDesInscrits', 'li', 'WITH',
//                        'li = :user')
//                    ->setParameter('user', $user)
//                    ->getQuery()->getResult();
//                dump($queryIns);
//
//
//                $query = $query->innerJoin('sorties.listeDesInscrits', 'li', 'WITH',
//                    'li NOT IN (:sorties_user_inscrit)')
//                    ->setParameter('sorties_user_inscrit', $queryIns);


//                $queryIns = $this->createQueryBuilder('sortiesIns')
//                    ->innerJoin('sortiesIns.listeDesInscrits', 'li', 'WITH',
//                        'li = :user')
//                    ->setParameter('user', $user)
//                    ->getQuery()->getResult();
//                dump($queryIns);
//                $query->andWhere('sorties.id NOT IN (:sorties_user_inscrit)')->setParameter('sorties_user_inscrit', $queryIns);




                $query = $query->andWhere(
                   ':user NOT MEMBER OF sorties.listeDesInscrits')
                    ->setParameter('user', $user)
                   ;

//                dump($query->getDQL());
            }
            if ($booleanSortiesPassees) {
                //sorties passées
                $query =
                    $query->andWhere('sorties.etatSortie > 4');

            }else{
                //sorties actuelles
                $query =
                    $query->andWhere('sorties.etatSortie <= 4');


            }

        return $query->getQuery()->getResult();

        }
  
}
