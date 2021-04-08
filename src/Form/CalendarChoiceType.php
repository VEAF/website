<?php

namespace App\Form;

use App\Entity\Calendar\Choice;
use App\Entity\Module;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarChoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        $builder
            ->add('module', EntityType::class,
                [
                    'class' => Module::class,
                    'label' => 'Module',
                    'required' => false,
                    'placeholder' => '-- Aucun --',
                    'attr' => [
                        'class' => 'select2auto remote-select2auto',
                    ],
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('m')
                            ->andWhere('m.type IN (:types)')
                            ->setParameter('types', [Module::TYPE_AIRCRAFT, Module::TYPE_HELICOPTER, Module::TYPE_SPECIAL])
                            ->orderBy('m.name', 'ASC');
                    },
                    'group_by' => function (Module $module, $key, $value) {
                        switch ($module->getType()) {
                            case Module::TYPE_AIRCRAFT:
                                return 'Avion';
                            case Module::TYPE_HELICOPTER:
                                return 'Hélicoptère';
                            case Module::TYPE_SPECIAL:
                            default:
                                return 'Spécial';
                        }
                    },
                ])
            ->add('task', ChoiceType::class,
                [
                    'choices' => array_flip(Choice::TASKS),
                ])
            ->add('comment', TextType::class, [
                'label' => 'Commentaire',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Choice::class,
            'user' => Choice::class,
        ]);
    }
}
