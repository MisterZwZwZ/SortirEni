<?php

namespace App\Form;

use App\Entity\Sorties;
use App\Entity\Campus;
use App\Entity\Villes;
use App\Repository\CampusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', TextType::class, [
           'label' => 'Nom de la sortie: ',
           'trim' => true,
           'required' => true,
        ]);

        $builder->add('dateHeureDebut', DateTimeType::class, [
            'label' => 'Date et heure de la sortie: ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('dateLimiteInscription', DateType::class, [
            'label' => 'Date limite d\'inscription: ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('nbIncriptionsMax', NumberType::class, [
            'label' => 'Nombre de places : ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('duree', NumberType::class, [
            'label' => 'Durée: ',
            'trim' => true,
            'required' => true,
        ]);

        $builder->add('infosSortie', TextareaType::class, [
            'label' => 'Descritpion et infos: ',
            'trim' => true,
            'required' => false,
        ]);

//        $builder->add('campus', EntityType::class, [
//            'label' => 'Campus:',
//            'mapped' => true,
//            'class' => Campus::class,
//            'query_builder' => function (CampusRepository $campusRepository){
//            return $campusRepository->createQueryBuilder('campus')
//                ->orderBy('campus.nomCampus', 'ASC');
//            },
//        ]);

//        $builder->add('ville', ChoiceType::class, [
//            'label' => 'Ville:',
//            'choices' => [
//                'Choix 1' => 1,
//                'Choix 2' => 2,
//                'Choix 3' => 3,
//                'Choix 4' => 4,
//            ]
//        ]);
//
//        $builder->add('lieu', ChoiceType::class, [
//            'label' => 'Lieu:',
//            'choices' => [
//                'Choix 1' => 1,
//                'Choix 2' => 2,
//                'Choix 3' => 3,
//                'Choix 4' => 4,
//            ]
//        ]);
//
//        $builder->add('ajoutLieu', SubmitType::class, [
//            'label' => '+',
//        ]);
//
//        $builder->add('rue', TextType::class, [
//            'label' => 'Rue:',
//            'trim' => true,
//            'data' => 'Rue de l\'utilisateur',
//            'disabled' => true,
//        ]);
//
//        $builder->add('codePostal', TextType::class, [
//            'label' => 'Code Postal:',
//            'trim' => true,
//            'data' => 'CP de l\'utilisateur',
//            'disabled' => true,
//        ]);
//
//        $builder->add('latitude', TextType::class, [
//            'label' => 'Latitude:',
//            'trim' => true,
//        ]);
//
//        $builder->add('longitude', TextType::class, [
//            'label' => 'Longitude:',
//            'trim' => true,
//        ]);
    }
}