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

class MobicoopForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('givenName', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'First name']
            ])
            ->add('familyName', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Last name']
            ])
            ->add('status', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Beneficiary' => 1,
                    'Volunteer' => 2,
                ],
                'data' => $options['status'] ?? $options['status'],
                'placeholder' => 'Status',
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'email@example.com'],
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Password'],
            ])
            ->add('confirm_password', PasswordType::class,[
                'label'=> false,
                'attr'=> ['placeholder' => 'Confirm password']
            ])
            ->add('telephone', TelType::class, [
                'label' => false,
                'attr' => ['placeholder' => '0123456789'],
            ])
            ->add('gender', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Female' => 1,
                    'Male' => 2,
                    'Other' => 3,
                ],
                'data' => $options['gender'] ?? $options['gender'],
                'placeholder' => 'Gender',
            ])
            ->add('birthDate', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'datepicker',
                    'placeholder' => 'Birth date'
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
