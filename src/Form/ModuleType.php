<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\ModuleRole;
use App\Entity\ModuleSystem;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => array_flip(Module::TYPES),
            ])
            ->add('name', TextType::class, ['required' => true])
            ->add('longName', TextType::class, ['required' => true])
            ->add('code', TextType::class, ['required' => true]);

        $builder->add('landingPage', CheckboxType::class, ['required' => false]);
        $builder->add('landingPageNumber', IntegerType::class, ['required' => true]);

        $builder
            ->add('roles', EntityType::class, [
                'class' => ModuleRole::class,
                'choice_label' => 'name',
                'label' => 'Rôles',
                'multiple' => true,
                'expanded' => true,
            ]);

        $builder
            ->add('systems', EntityType::class, [
                'class' => ModuleSystem::class,
                'choice_label' => 'name',
                'label' => 'Systèmes',
                'multiple' => true,
                'expanded' => true,
            ]);

        $builder
            ->add('period', ChoiceType::class, [
                'choices' => array_flip(Module::PERIODS),
                'label' => 'Période',
            ]);

        $builder
            ->add('imageHeader', FileType::class, [
                'label' => 'Image (format large / Header - 5:1 conseillé)',
                'mapped' => false,
                'required' => false,
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

        $builder
            ->add('image', FileType::class, [
                'label' => 'Image (format 16/9)',
                'mapped' => false,
                'required' => false,
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
            'data_class' => Module::class,
        ]);
    }
}
