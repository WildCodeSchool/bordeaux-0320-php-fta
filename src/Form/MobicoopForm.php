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
use Vich\UploaderBundle\Form\Type\VichFileType;


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
                'mapped' => !$options['edit'],
            ])
            ->add('password', PasswordType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Password'],
                'mapped' => !$options['edit'],
            ])
            ->add('telephone', TelType::class, [
                'label' => false,
                'attr' => ['placeholder' => '0123456789'],
                'mapped' => !$options['edit'],
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
                'mapped' => !$options['edit'],
            ])
            
            ->add('birthDate', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'datepicker',
                    'placeholder' => 'Birthday'

                    ],
                'mapped' => !$options['edit'],
            ]);
        if ($options['edit']) {
            $builder->add('pictureFile', VichFileType::class, [
                'label' => 'Picture',
                'required' => false,
                'allow_delete' => true, // not mandatory, default is true
                'download_uri' => true, // not mandatory, default is true

            ]);
        }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'gender' => null,
            'status' => null,
            'edit'   => false,
        ]);
    }
}
