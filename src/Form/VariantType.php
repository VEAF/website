<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Variant;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
                [
                    'required' => true,
                ])
            ->add('module', EntityType::class,
                [
                    'class' => Module::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->andWhere('m.type IN (:types)')
                            ->setParameter('types', [Module::TYPE_AIRCRAFT, Module::TYPE_HELICOPTER])
                            ->orderBy('m.code', 'ASC');
                    },
                    'required' => false,
                    'attr' => [
                        'class' => 'select2 select2auto',
                    ],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Variant::class,
        ]);
    }
}
