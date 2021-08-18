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

class RechercheSortiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('campus', EntityType::class,[
            'label'=>'Campus',
            'required'=>false,
            'class'=> Campus::class,
            'query_builder'=> function (CampusRepository $campusRepository){
                return $campusRepository->createQueryBuilder('campus')
                    ->orderBy('campus.nomCampus','ASC');

            },
            'choice_label'=>'nomCampus',
            'mapped'=>false,

        ]);
        $builder->add('nom', SearchType::class,[
            'required'=> false,
            'trim'=> true,
            'label'=>'Le nom de la sortie :',
            'empty_data'=>'saisir mots clés d\'une sortie'
        ]);
        $builder->add('dateHeureDebut', DateTimeType::class,[
            'required'=> false,
            'label'=>'Entre :'
        ]);
        $builder->add('dateLimiteInscription',DateTimeType::class,[
            'required'=> false,
            'label'=>'et :'
        ]);
        $builder->add('SortiesOrganisateurs', CheckboxType::class,[
            'label'=>'SortiesFixtures dont je suis l\'organisateur(trice)',
            'mapped'=>false,
            'required'=> false,
            'trim'=>true,
        ]);
        $builder->add('SortiesInscrits', CheckboxType::class,[
            'label'=>'Sortie auxquelles je suis inscrit(e)',
            'trim'=>true,
            'required'=> false,
            'mapped'=>false,
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
            'data_class' => Sorties::class,
        ]);
    }
}
