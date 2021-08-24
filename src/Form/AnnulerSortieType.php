<?php

namespace App\Form;

use PHPUnit\Framework\Constraint\IsEmpty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use function PHPUnit\Framework\isEmpty;

class AnnulerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('motif', TextareaType::class,[
                'required'=>false,
                'mapped'=>false,
                'label'=>'Motif :',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min'=> 50,
                                'minMessage'=>'Le nombre minimum de caractère est de 50',
                                'max'=>500,
                                'maxMessage'=>'Le nombre maximum de caractère est de 500'
                            ]
                    ),
            ]
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
            'validation_groups'=>['annulation']

        ]);
    }
}

