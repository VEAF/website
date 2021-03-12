<?php

namespace App\Validator\Menu\Item;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Type extends Constraint
{
    public $invalidType = 'Le type "{{ value }}" n\'est pas valide.';
    public $needUrl = 'Une url (redirection) est obligatoire pour un élément de type {{ value }}';
    public $needPage = 'Une page est obligatoire pour un élément de type {{ value }}';
    public $needLink = 'Une url personnalisée est obligatoire pour un élément de type {{ value }}';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
