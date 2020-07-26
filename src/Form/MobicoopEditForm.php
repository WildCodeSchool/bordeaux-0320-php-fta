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

class MobicoopEditForm extends AbstractType
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
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'email@example.com'],
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
            ]);
    }
}
