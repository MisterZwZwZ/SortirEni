<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Sorties;

use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class RechercheSortiesType extends AbstractType
{
    private ?Security $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        dump($this->security->getUser()->getCampusUser()->getNomCampus());
//        exit();

        $builder->add('campus', EntityType::class,[
            'label'=>'Campus',
            'required'=>false,
            'class'=> Campus::class,
            'query_builder'=> function (CampusRepository $campusRepository){
                return $campusRepository->createQueryBuilder('campus')
                    ->andWhere('campus.id = :idCampusUser')
                    ->setParameter('idCampusUser',$this->security->getUser()->getCampusUser()->getId())
                    ->orderBy('campus.nomCampus','ASC');

            },
            'choice_label'=>'nomCampus',
            'mapped'=>false,

        ]);
        $builder->add('nomRecherche', SearchType::class,[
            'required'=> false,
            'mapped'=>false,
            'trim'=> true,
            'label'=>'Le nom de la sortie :',
            'attr'=>['placeholder'=>'saisir mots clés d\'une sortie']
        ]);
        $builder->add('dateHeureDebutRecherche', DateTimeType::class,[
            'required'=> false,
            'mapped'=>false,
            'label'=>'Entre :'
        ]);
        $builder->add('dateFinRecherche',DateTimeType::class,[
            'required'=> false,
            'mapped'=>false,
            'label'=>'et :'

        ]);
        $builder->add('SortiesOrganisateurs', CheckboxType::class,[
            'label'=>'Sorties dont je suis l\'organisateur(trice)',
            'mapped'=>false,
            'required'=> false,
            'trim'=>true,
        ]);
        $builder->add('SortiesInscrits', CheckboxType::class,[
            'label'=>'Sortie auxquelles je suis inscrit(e)',
            'trim'=>true,
            'required'=> false,
            'mapped'=>false, //ne cherche pas a injecter la variable dans les attributs de l'entity
        ]);
        $builder->add('SortiesNonInscrits', CheckboxType::class,[
            'label'=>'Sortie auxquelles je ne suis pas inscrit(e)',
            'trim'=>true,
            'required'=> false,
            'mapped'=>false,
        ]);
        $builder->add('SortiesPassees', CheckboxType::class,[
            'label'=>'SortiesFixtures passées',
            'trim'=>true,
            'required'=> false,
            'mapped'=>false,
        ]);
        $builder->add('submit', SubmitType::class,[
            'label'=>'Recherche'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class, //relie le formulaire à l'entity
        ]);
    }
}
