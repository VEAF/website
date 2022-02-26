<?php

namespace App\Security\Voter;

use App\Entity\Server;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ServerVoter extends Voter
{
    const EDIT_CONTROL = 'EDIT_CONTROL';

    protected function supports($attribute, $subject)
    {
        if (!($subject instanceof Server)) {
            return false;
        }

        return in_array($attribute, [self::EDIT_CONTROL]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $member */
        $member = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$member instanceof UserInterface) {
            return false;
        }

        /** @var Server $server */
        $server = $subject;

        switch ($attribute) {
            case self::EDIT_CONTROL:
                if ($member->isMember()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
