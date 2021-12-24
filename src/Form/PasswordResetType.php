<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class PasswordResetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled'=> true
            ])
            ->add('firstName', TextType::class, [
                'disabled' => true
            ])
            ->add('lastName', TextType::class, [
                'disabled'=> true
            ])
            ->add('old_password', PasswordType::class, [
                'mapped'=>false,
                'label'=>'Votre mot de passe actuel',
                'attr'=> [
                    'placeholder'=>'veuillez entrer votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'mapped'=>false,
                'type'=> PasswordType::class,
                'invalid_message'=> 'le mot de passe et la confiormation doivent etre identique',
                'label'=>'Mon nouveau mot de passe',
                'required'=>true,
                'first_options'=> [
                    'label'=>'saisir votre nouveau mot de passe.',
                    'attr'=> [
                        'placeholder'=>'saisir votre nouveau mot de passe.'
                    ]
                    ],
                'second_options'=> [
                    'label'=>'Confirmez votre nouveau mot de passe',
                    'attr'=> [
                        'placeholder'=>'Confirmez votre mot de passe'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mise A jour'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
