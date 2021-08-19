<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo : ',
                'trim' => true,
                'required' => true,
            ])

            ->add('prenom', TextType::class, [
                'label' => 'Prenom : ',
                'trim' => true,
                'required' => true,
            ])

            ->add('nom', TextType::class, [
                'label' => 'Nom : ',
                'trim' => true,
                'required' => true,
            ])

            ->add('email', EmailType::class, [
                'label' => 'Email : ',
                'trim' => true,
                'required' => true,
            ])

            ->add('telephone', TextType::class, [
                'label' => 'Telephone : ',
                'trim' => false,
                'required' => true,
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe: '],
                'second_options' => ['label' => 'Confirmez votre Mot de passe: '],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'votre mot de passe doit être de {{ limit }} caractères minimum',
                        'max' => 30,
                        'maxMessage' => 'votre mot de passe doit être de {{ limit }} caractères maximum',
                    ]),
                ]
            ])

            ->add('campusUser',EntityType::class, [
                'class'  => Campus::class,
                'choice_label' => 'nomCampus',
                'label' => 'Campus : ',
                'required' => true,
                'expanded' => false,
                'multiple' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
