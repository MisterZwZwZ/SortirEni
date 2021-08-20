<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Villes;
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
use Symfony\Component\Security\Core\Security;

class SortieType extends AbstractType
{
    private ?Security $security = null;

    public function __construct(Security $security) {
        $this->security = $security;
    }

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
            'required' => true,
            'input'  => 'datetime_immutable',
            'widget' => 'single_text',
        ]);

        $builder->add('dateLimiteInscription', DateType::class, [
            'label' => 'Date limite d\'inscription: ',
            'required' => true,
            'input'  => 'datetime_immutable',
            'widget' => 'single_text',
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

        $builder->add('campus', TextType::class,[
            'label'=>'Campus',
            'required'=>false,
            'mapped'=>false,
            'data' => $this->security->getUser()->getCampusUser()->getNomCampus(),
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
        ]);

          $builder->add('ajoutLieu', SubmitType::class, [
              'label' => '+',
          ]);

          $builder->add('rue', TextType::class, [
              'label' => 'Rue:',
              'trim' => true,
              'mapped'=>false,
          ]);

          $builder->add('codePostal', TextType::class, [
              'label' => 'Code Postal:',
              'trim' => true,
              'mapped'=>false,
          ]);
  }
}