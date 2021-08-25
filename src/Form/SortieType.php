<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Villes;
use App\Repository\VillesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
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
//            'input'  => 'datetime_immutable',
            'widget' => 'single_text',
        ]);

        $builder->add('dateLimiteInscription', DateType::class, [
            'label' => 'Date limite d\'inscription: ',
            'required' => true,
//            'input'  => 'datetime_immutable',
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
            'mapped'=>false,
            'data' => $this->security->getUser()->getCampusUser()->getNomCampus(),
        ]);

        $builder->add('villes', EntityType::class,[
            'label'=>'Ville',
            'required'=>false,
            'placeholder' => 'Sélectionner une ville',
            'class'=> Villes::class,
            'choice_label'=>'nom',
            'mapped'=>false,
        ]);

        $builder->get('villes')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $ville = $event->getForm()->getData();
                $form = $event->getForm()->getParent();
                $this->addLieuxField($form, $ville);
                dump($ville);

            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event){
                $form = $event->getForm();
                $sortie = $event->getData();
                $lieu = $sortie->getLieu();
                if ($lieu){
                    $ville = $lieu->getVille();
                    $this->addLieuxField($form, $ville);
                    $form->get('villes')->setData($ville);
                    $form->get('lieu')->setData($lieu);
                }else{
                    $this->addLieuxField($form, null);
                }
            }
        );
    }

    /**
     * Rajoute un champ lieux au formulaire
     * @param FormInterface $form
     * @param Villes $ville
     */
    private function addLieuxField(FormInterface $form, ?Villes $ville){
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'lieu',
            EntityType::class,
            null,
            [
                'label'=>'Lieux :',
                'required' => false,
                'class'=> Lieux::class,
                'placeholder' => $ville ? 'Sélectionner un lieu' : 'Sélectionner une ville en premier',
                'auto_initialize' => false,
                'choices' => $ville ? $ville->getLieuxRattaches() : [],
            ]
        );
        $form->add($builder->getForm());
    }

}
