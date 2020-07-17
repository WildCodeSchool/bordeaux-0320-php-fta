<?php

namespace App\Form;

use App\Entity\Arrival;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArrivalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Nom']
            ])
            ->add('category', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    'Administration' => '<i class="material-icons">location_city</i>',
                    'Santé'          => '<i class="material-icons">local_hospital</i>',
                    'Logement'       => '<i class="material-icons">home</i>',
                    'Transport'      => '<i class="material-icons">directions_transit</i>',
                ],
                'placeholder' => 'Catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Arrival::class,
        ]);
    }
}
