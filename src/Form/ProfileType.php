<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', TextType::class,
                [
                    'required' => true,
                    'label' => 'Mon pseudo',
                ])
            ->add('forum', TextType::class,
                [
                    'required' => false,
                    'label' => 'Mon pseudo sur le forum',
                ])
            ->add('discord', TextType::class,
                [
                    'required' => false,
                    'label' => 'Mon pseudo sur discord',
                ])
            ->add('simBms', CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'J\'ai le simulateur Falcon 4 BMS',
                ])
            ->add('simDcs', CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'J\'ai le simulateur Digital Combat Simulator - DCS World',
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
