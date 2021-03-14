<?php

namespace App\Form;

use App\Entity\Menu\Item;
use App\Entity\Page;
use App\Entity\Url;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenuItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class,
                [
                    'label' => 'Libellé',
                    'required' => true,
                ]
            )
            ->add('type', ChoiceType::class,
                [
                    'required' => true,
                    'expanded' => true,
                    'choices' => array_flip(Item::TYPES),
                ])
            ->add('icon', TextType::class,
                [
                    'label' => 'Icône',
                    'required' => false,
                ])
            ->add('themeClasses', TextType::class,
                [
                    'label' => 'Classes CSS',
                    'required' => false,
                ])
            ->add('enabled', CheckboxType::class,
                [
                    'label' => 'Activé',
                    'required' => false,
                ])
            ->add('position', IntegerType::class, [
                'label' => 'Position',
                'required' => true,
            ])
            ->add('link', \Symfony\Component\Form\Extension\Core\Type\UrlType::class,
                [
                    'label' => 'Url personnalisée',
                    'required' => false,
                ])
            ->add('menu', EntityType::class,
                [
                    'label' => 'Menu',
                    'class' => Item::class,
                    'placeholder' => '-',
                    'required' => false,
                    'attr' => [
                        'class' => 'select2auto form-control',
                    ],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->andWhere('m.type IN (:types)')
                            ->setParameter('types', [Item::TYPE_MENU])
                            ->orderBy('m.label', 'ASC');
                    },
                ])
            ->add('url', EntityType::class,
                [
                    'label' => 'Url de redirection',
                    'class' => Url::class,
                    'placeholder' => '-',
                    'required' => false,
                    'attr' => [
                        'class' => 'select2auto form-control',
                    ],
                ])
            ->add('page', EntityType::class,
                [
                    'label' => 'Page personnalisée',
                    'class' => Page::class,
                    'placeholder' => '-',
                    'required' => false,
                    'attr' => [
                        'class' => 'select2auto form-control',
                    ],
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
