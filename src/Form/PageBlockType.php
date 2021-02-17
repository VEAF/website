<?php

namespace App\Form;

use App\Entity\PageBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('type')
            ->add('content',
                TextareaType::class,
                [
                    'attr' => ['class' => 'editor', 'rows' => 20],
                    'label' => 'Contenu',
                ])
            ->add('number',
                IntegerType::class,
                ['required' => false,
                    'label' => 'Ordre',
                ])
            ->add('enabled',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'Activé',
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PageBlock::class,
        ]);
    }
}
