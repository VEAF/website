<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Player;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class PlayerLinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', Select2EntityType::class, [
                'multiple' => false,
                'remote_route' => 'admin_dcs_player_autocomplete',
                'remote_params' => [], // static route parameters for request->query
                'class' => User::class,
                'primary_key' => 'id',
                'text_property' => 'nickname',
                'property' => 'nickname',
                'minimum_input_length' => 3,
                'page_limit' => 10,
                'allow_clear' => false,
                'delay' => 250,
                'language' => 'fr',
                'placeholder' => 'Sélectionner un utilisateur',
                'callback' => function (QueryBuilder $qb, $data) {
                    $qb->andWhere('e.player is null');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
