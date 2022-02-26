<?php

namespace App\Form;

use App\Entity\Calendar\Slot;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarSlotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'placeholder' => 'pseudo si invité',
                ],
                'required' => false,
            ])
            ->add('user', EntityType::class,
                [
                    'required' => false,
                    'class' => User::class,
                    'placeholder' => '',
                    'choice_label' => 'nickname',
                    'query_builder' => function (EntityRepository $er) {
                        $qb = $er->createQueryBuilder('u')
                            ->orderBy('u.nickname', 'ASC');

                        return $qb;
                    },
                    'attr' => [
                        'class' => 'select2 select2auto',
                    ],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Slot::class,
        ]);
    }
}
