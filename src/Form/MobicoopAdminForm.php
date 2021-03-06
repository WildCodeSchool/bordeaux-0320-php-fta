<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MobicoopAdminForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('givenName', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Prénom'],
                'required' => true,
            ])
            ->add('familyName', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Nom'],
                'required' => true,
            ])
            ->add('gender', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Femme' => 1,
                    'Homme' => 2,
                    'Autre' => 3,
                ],
                'data' => $options['gender'],
                'placeholder' => 'Genre',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Adresse email'],
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Mot de passe *',
                    'pattern' => '^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$',
                    'title' => 'Le mot de passe doit faire au moins 6 caractères et avoir une 
                    lettre en capitale, une lettre en minuscule et un chiffre',
                    ],
                'required' => true,
            ])
            ->add('telephone', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Téléphone',
                    'pattern' => '^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$',
                    'title' => 'Only numbers and symbol (+) allowed',
                ],
                'required' => true,
            ])
            ->add('birthDate', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'datepicker',
                    'placeholder' => 'Né(e) le'
                ],
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'gender' => null,
        ]);
    }
}
