<?php

namespace App\Form;

use App\Entity\Calendar\Event;
use App\Entity\Module;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CalendarEventType extends AbstractType
{
    private string $website;

    public function __construct(string $website)
    {
        $this->website = $website;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateTimeType::class,
                [
                    'required' => true,
                    'widget' => 'single_text',
                    'label' => 'Début',
                ])
            ->add('endDate', DateTimeType::class,
                [
                    'required' => true,
                    'widget' => 'single_text',
                    'label' => 'Fin',
                ])
            ->add('type', ChoiceType::class, [
                'choices' => array_flip(Event::EVENTS),
            ])
            ->add('simDcs', CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'Simulateur DCS',
                ]
            );
        if ('veaf' === $this->website) {
            $builder->add('simBms', CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'Simulateur BMS',
                ]);
        }

        $builder
            ->add('title', TextType::class,
                [
                    'required' => true,
                    'label' => 'Titre',
                ])
            ->add('description', TextareaType::class,
                [
                    'required' => false,
                    'label' => 'Description',
                ]
            )
            ->add('restrictions', ChoiceType::class,
                [
                    'label' => 'Participation réservée aux ... (ne rien cocher si ouvert à tout le monde)',
                    'choices' => [
                        array_flip(Event::RESTRICTIONS),
                    ],
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                ]
            )
            ->add('map', EntityType::class,
                [
                    'class' => Module::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->andWhere('m.type = :type')
                            ->setParameter('type', Module::TYPE_MAP)
                            ->orderBy('m.name', 'ASC');
                    },
                    'required' => false,
                    'label' => 'Carte',
                    'placeholder' => '-',
                    'attr' => [
                        'class' => 'select2auto form-control',
                    ],
                ])
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
        $builder
            ->add('image', FileType::class, [
                'label' => 'Image (format 16/9)',
                'mapped' => false,
                'required' => false,
                'attr' => [
                    'class' => 'custom-file-input form-control',
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '20m',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Format accepté: uniquement les images jpg et png',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
