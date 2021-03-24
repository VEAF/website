<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionMakerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $groups = User::GROUPS;
        unset($groups[User::GROUP_OFFICE]);

        $builder
            ->add('group', ChoiceType::class, [
                'label' => 'Groupe',
                'choices' => array_flip($groups),
                'multiple' => false,
                'expanded' => true,
            ]);

        $builder
            ->add('map', EntityType::class, [
                'class' => Module::class,
                'label' => 'Carte',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->andWhere('m.type = :type')
                        ->setParameter('type', Module::TYPE_MAP)
                        ->orderBy('m.name', 'ASC');
                },
                'required' => true,
                'placeholder' => '-',
                'attr' => [
                    'class' => 'select2auto form-control',
                ],
            ]);

        $builder
            ->add('modules', EntityType::class,
                [
                    'class' => Module::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->andWhere('m.type IN (:types)')
                            ->setParameter('types', [Module::TYPE_AIRCRAFT, Module::TYPE_HELICOPTER, Module::TYPE_SPECIAL])
                            ->orderBy('m.name', 'ASC');
                    },
                    'required' => false,
                    'multiple' => true,
                    'attr' => [
                        'class' => 'select2auto',
                    ],
                ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
