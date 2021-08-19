<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Entity\Campus;
use App\Entity\Villes;
use App\Repository\CampusRepository;
use App\Repository\LieuxRepository;
use App\Repository\VillesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            'attr' => ['placeholder' => 'Nom de la sortie'],
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
            'attr' => ['placeholder' => 'Nombre de places maximum'],
        ]);

        $builder->add('duree', NumberType::class, [
            'label' => 'Durée: ',
            'trim' => true,
            'required' => true,
            'attr' => ['placeholder' => 'Durée de la sortie'],
        ]);

        $builder->add('infosSortie', TextareaType::class, [
            'label' => 'Descritpion et infos: ',
            'trim' => true,
            'required' => false,
            'attr' => ['placeholder' => 'Description et infos de la sortie'],
        ]);

        $builder->add('campus', EntityType::class,[
            'label'=>'Campus',
            'required'=>false,
            'disabled' => true,
            'class'=> Campus::class,
            'query_builder'=> function (CampusRepository $campusRepository){
                return $campusRepository->createQueryBuilder('campus')
                    ->orderBy('campus.nomCampus','ASC');

            },
            'choice_label'=>'nomCampus',
            'mapped'=>false,

        ]);

        $builder->add('villes', EntityType::class,[
            'label'=>'Ville',
            'required'=>false,
            'class'=> Villes::class,
            'query_builder'=> function (VillesRepository $villesRepository){
                return $villesRepository->createQueryBuilder('villes')
                    ->orderBy('villes.nom','ASC');

            },
            'choice_label'=>'nom',
            'mapped'=>false,
            'attr' => ['placeholder' => 'Choisir une ville'],

        ]);

        $builder->add('lieux', EntityType::class,[
            'label'=>'Lieux',
            'required'=>false,
            'class'=> Lieux::class,
            'query_builder'=> function (LieuxRepository $lieuxRepository){
                return $lieuxRepository->createQueryBuilder('lieux')
                    ->orderBy('lieux.nom','ASC');

            },
            'choice_label'=>'nom',
            'mapped'=>false,
            'attr' => ['placeholder' => 'Choisir un lieux'],

        ]);

          $builder->add('ajoutLieu', SubmitType::class, [
              'label' => '+',
          ]);

          $builder->add('rue', EntityType::class, [
              'label' => 'Rue:',
              'trim' => true,
              'disabled' => true,
              'class'=> Lieux::class,
              'query_builder'=> function (LieuxRepository $lieuxRepository){
                  return $lieuxRepository->createQueryBuilder('lieux')
                      ->orderBy('lieux.rue','ASC');

              },
              'choice_label'=>'rue',
              'mapped'=>false,
          ]);

          $builder->add('codePostal', EntityType::class, [
              'label' => 'Code Postal:',
              'trim' => true,
              'disabled' => true,
              'class'=> Villes::class,
              'query_builder'=> function (VillesRepository $villesRepository){
                  return $villesRepository->createQueryBuilder('villes')
                      ->orderBy('villes.codePostal','ASC');

              },
              'choice_label'=>'codePostal',
              'mapped'=>false,
          ]);

          $builder->add('latitude', EntityType::class, [
              'label' => 'Latitude:',
              'trim' => true,
              'class'=> Lieux::class,
              'query_builder'=> function (LieuxRepository $lieuxRepository){
                  return $lieuxRepository->createQueryBuilder('lieux')
                      ->orderBy('lieux.latitude','ASC');

              },
              'mapped'=>false,
              'attr' => ['placeholder' => 'Latitude du lieux'],
          ]);

        $builder->add('longitude', EntityType::class, [
            'label' => 'Longitude:',
            'trim' => true,
            'class'=> Lieux::class,
            'query_builder'=> function (LieuxRepository $lieuxRepository){
                return $lieuxRepository->createQueryBuilder('lieux')
                    ->orderBy('lieux.longitude','ASC');

            },
            'mapped'=>false,
            'attr' => ['placeholder' => 'Longitude du lieux'],
        ]);
  }
}