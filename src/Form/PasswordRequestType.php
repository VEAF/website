<?php

namespace App\Form;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class);

        $builder->add('captcha', Recaptcha3Type::class, [
            'constraints' => new Recaptcha3(['message' => 'Erreur lors de la vérification anti-bot: {{ errorCodes }}']),
            'action_name' => 'homepage',
        ]);
    }
}
