<?php

namespace App\Form;

use App\Entity\Calendar\Event;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarEventAdminType extends CalendarEventType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('owner', EntityType::class,
                [
                    'class' => User::class,
                    'required' => true,
                    'placeholder' => '-',
                    'attr' => [
                        'class' => 'select2auto form-control',
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
