<?php

namespace App\Form;

use App\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Titre',
                ])
            ->add('enabled',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'Activée',
                ])
            ->add('route',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Code route',
                ])
            ->add('path',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'Url',
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
