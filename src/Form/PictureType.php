<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;

class PictureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imageFile', VichFileType::class, [
            'label' => false,
            'required' => true,
            'allow_delete' => true,
            'download_label' => 'Download',
            'download_uri' => true,
        ]);
    }
}
