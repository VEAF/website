<?php

namespace App\Twig;

use App\Entity\User;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UserExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('user_role', [$this, 'userRole']),
        ];
    }

    public function userRole(string $role)
    {
        return User::getRoleAsString($role);
    }
}
