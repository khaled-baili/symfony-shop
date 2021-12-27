<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre nom d\'adresse',
                'attr' => [
                    'placeholder' => 'Entrez votre nom adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prenom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom prenom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre nom de company (optionnel)',
                'attr' => [
                    'placeholder' => 'Entrez votre nom company'
                ]
            ])
            ->add('adress', TextType::class, [
                'label' => 'Votre adresse',
                'attr' => [
                    'placeholder' => 'Entrez votre adresse'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' => 'Votre code postal',
                'attr' => [
                    'placeholder' => 'Entrez votre code postal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Entrez votre nom de ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'Votre pays',
                'attr' => [
                    'placeholder' => 'Entrez votre nom de pays'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Votre numero telephone',
                'attr' => [
                    'placeholder' => 'Entrez votre numero telephone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter adresse',
                'attr'=> [
                    'class'=>'btn-block btn-info'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
