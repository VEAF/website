<?php

namespace App\Form;

use App\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

class FileUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('file', FileType::class, [
                'label' => 'Fichier',
                'mapped' => false,
                'required' => true,
//                'constraints' => [
//                    new File([
//                        'maxSize' => '20M',
//                        'mimeTypes' => [
//                            'application/png',
//                            'application/jpg',
//                            'application/jpeg',
//                        ],
//                        'mimeTypesMessage' => "Merci d'envoyer un format de fichier autorisé",
//                    ])
//                ],
            ])
        ;
    }
}
