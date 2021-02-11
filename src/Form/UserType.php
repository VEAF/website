<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = [];
        foreach (User::ROLES as $role) {
            $pos = strpos('_', $role);
            if ($pos) {
                $roles[substr($role, $pos)] = $role;
            } else {
                $roles[$role] = $role;
            }
        }

        $builder
            ->add('email', EmailType::class)
//            ->add('roles', ChoiceType::class,
//                [
//                    'choices' => $roles,
//                ])
            ->add('nickname')
            ->add('simDcs', CheckboxType::class, [
                'required' => false,
            ])
            ->add('simBms', CheckboxType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_flip(User::STATUSES),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
