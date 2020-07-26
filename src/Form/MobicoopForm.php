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
                'attr' => ['placeholder' => 'First name'],
                'required' => true,
            ])
            ->add('familyName', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Last name'],
                'required' => true,
            ])
            ->add('status', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Beneficiary' => 1,
                    'Volunteer' => 2,
                ],
                'data' => $options['status'],
                'placeholder' => 'Status',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'email@example.com'],
                'required' => true,
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Password',
                    'pattern' => '^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$',
                    'title' => '6 characters minimum (with at leat one upper 
                    case letter, one lower case letter and one numeric digit)',
                    ],
                'required' => true,
            ])
            ->add('confirm_password', PasswordType::class,[
                'label'=> false,
                'attr'=> [
                    'placeholder' => 'Confirm password',
                    'pattern' => '^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,20}$',
                    'title' => 'You must write the same password as above',
                    ],
                'required' => true,
            ])
            ->add('telephone', TelType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => '0123456789',
                    'pattern' => '^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$',
                    'title' => 'Only numbers and symbol (+) allowed',
                ],
                'required' => true,
            ])
            ->add('gender', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Female' => 1,
                    'Male' => 2,
                    'Other' => 3,
                ],
                'data' => $options['gender'],
                'placeholder' => 'Gender',
                'required' => true,
            ])
            ->add('birthDate', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'datepicker',
                    'placeholder' => 'Birth date'
                    ],
                'required' => true,
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
