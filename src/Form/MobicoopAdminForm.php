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
                'attr' => ['placeholder' => 'Prénom']
            ])
            ->add('familyName', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Nom']
            ])
            ->add('status', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Bénéficiaire' => 1,
                    'Accompagnant' => 2,
                ],
                'data' => $options['status'] ?? $options['status'],
                'placeholder' => 'Statut'
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Adresse email']
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Mot de passe *']
            ])
            ->add('telephone', TelType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Téléphone']
            ])
            ->add('gender', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Femme' => 1,
                    'Homme' => 2,
                    'Autre' => 3,
                ],
                'data' => $options['gender'] ?? $options['gender'],
                'placeholder' => 'Genre',
            ])
            ->add('birthDate', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'datepicker',
                    'placeholder' => 'Né(e) le'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'gender' => null,
            'status' => null,
        ]);
    }
}
