<?php

namespace App\Form;

use App\Entity\Arrival;
use App\Entity\Departure;
use App\Entity\Trip;
use App\Form\Type\DateTimePickerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateTimePickerType::class, ['mapped' => false])
            ->add('departure', EntityType::class, [
                'class' => Departure::class,
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Starting point'
            ])
            ->add('arrival', EntityType::class, [
                'class' => Arrival::class,
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Destination'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
