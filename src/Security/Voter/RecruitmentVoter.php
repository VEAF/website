<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class RecruitmentVoter extends Voter
{
    const PRESENTATION = 'PRESENTATION';
    const ADD_FLIGHT = 'ADD_FLIGHT';

    protected function supports($attribute, $subject)
    {
        if (in_array($attribute, [self::PRESENTATION])) {
            return true;
        }

        if (!($subject instanceof User)) {
            return false;
        }

        return in_array($attribute, [self::ADD_FLIGHT]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var User $member */
        $member = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$member instanceof UserInterface) {
            return false;
        }

        /** @var User $user */
        $user = $subject;

        switch ($attribute) {
            // admin and recruiters can mark presentation has done
            case self::PRESENTATION:
                if (in_array('ROLE_ADMIN', $member->getRoles()) || in_array('ROLE_RECRUITER', $member->getRoles())) {
                    return true;
                }
                break;
            case self::ADD_FLIGHT:
                if ($member->isMember()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
