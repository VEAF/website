<?php

namespace App\Form;

use App\Entity\Calendar\Flight;
use App\Entity\Module;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarFlightType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Nom de flight',
                ],
            ])
            ->add('mission', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'CAS, CAP, SEAD, ...',
                ],
            ])
            ->add('nbSlots', IntegerType::class, [
                'required' => false,
            ])
            //->add('event')
            ->add('aircraft', EntityType::class,
                [
                    'required' => false,
                    'class' => Module::class,
                    'placeholder' => '-- Module --',
                    'query_builder' => function (EntityRepository $er) {
                        $qb = $er->createQueryBuilder('m')
                            ->andWhere('m.type IN (:types)')
                            ->setParameter('types', [Module::TYPE_AIRCRAFT, Module::TYPE_HELICOPTER, Module::TYPE_SPECIAL])
                            ->orderBy('m.name', 'ASC');

                        return $qb;
                    },
                    'attr' => [
                        'class' => 'select2 select2auto',
                    ],
                ]
            )
            ->add('slots', CollectionType::class,
                [
                    'entry_type' => CalendarSlotType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'label' => false,
                ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Flight::class,
        ]);
    }
}
