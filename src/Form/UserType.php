<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
                [
                    'required' => true,
                    'label' => 'Email',
                ])
            ->add('roles', ChoiceType::class,
                [
                    'choices' => array_flip(User::ROLES),
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'Rôles',
                ])
            ->add('nickname', TextType::class,
                [
                    'required' => true,
                    'label' => 'Pseudo',
                ])
            ->add('discord', TextType::class,
                [
                    'required' => true,
                    'label' => 'Pseudo discord',
                ])
            ->add('forum', TextType::class,
                [
                    'required' => true,
                    'label' => 'Pseudo forum',
                ])
            ->add('simDcs', CheckboxType::class, [
                'required' => false,
                'label' => 'Simulateur DCS',
            ])
            ->add('simBms', CheckboxType::class, [
                'required' => false,
                'label' => 'Simulateur BMS',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_flip(User::STATUSES),
                'label' => 'Statut',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
