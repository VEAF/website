<?php

namespace App\Security;

use App\Entity\User;

class Restriction
{
    const LEVEL_ALL = 0;
    const LEVEL_GUEST = 1;
    const LEVEL_CADET = 2;
    const LEVEL_MEMBER = 3;

    const LEVELS = [
        self::LEVEL_ALL => 'tout le monde',
        self::LEVEL_GUEST => 'au moins invité',
        self::LEVEL_CADET => 'au moins cadet',
        self::LEVEL_MEMBER => 'membre',
    ];

    public function isGrantedToLevel(?User $user, int $level): bool
    {
        if (null === $user) {
            switch ($level) {
                case self::LEVEL_ALL:
                    return true;
                default:
                    return false;
            }
        } else {
            switch ($level) {
                case self::LEVEL_ALL:
                    return true;
                case self::LEVEL_GUEST:
                    return $user->isGuest() || $user->isCadet() || $user->isMember();
                case self::LEVEL_CADET:
                    return $user->isCadet() || $user->isMember();
                case self::LEVEL_MEMBER:
                    return $user->isMember();
                default:
                    return false;
            }
        }
    }
}