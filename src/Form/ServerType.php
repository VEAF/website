<?php

namespace App\Form;

use App\Entity\Server;
use App\Perun\Entity\Instance;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add('code', TextType::class,
                [
                    'required' => true,
                ]
            )
            ->add('perunInstance', EntityType::class,
                [
                    'class' => Instance::class,
                    'required' => false,
                    'attr' => [
                        'class' => 'select2',
                    ],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Server::class,
        ]);
    }
}
