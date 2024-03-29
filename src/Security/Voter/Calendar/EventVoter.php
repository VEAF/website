<?php

namespace App\Security\Voter\Calendar;

use App\Entity\Calendar\Event;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class EventVoter extends Voter
{
    const ADD = 'EVENT_ADD';
    const EDIT = 'EDIT';
    const VOTE = 'VOTE';
    const CHOICE = 'CHOICE';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::ADD]) ||
            in_array($attribute, [self::EDIT, self::VOTE, self::CHOICE]) && $subject instanceof Event;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // not associated
        switch ($attribute) {
            // only members can add events
            case self::ADD:
                return $user->isMember();
        }

        if (Event::class !== get_class($subject)) {
            return false;
        }

        /** @var Event $event */
        $event = $subject;

        switch ($attribute) {
            case self::EDIT:
                // owner can edit event
                if ($event->getOwner() === $user) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                // else, edit is not granted
                return false;
            case self::VOTE:
            case self::CHOICE:
                // if registration
                if (!$event->isRegistration()) {
                    return false;
                }
                // if event is finished
                if ($event->getEndDate()->getTimestamp() < time()) {
                    return false;
                }
                // restrictions on this event ?
                if (count($event->getRestrictions()) > 0) {
                    if (!($event->hasRestriction(Event::RESTRICTION_MEMBER) && $user->isMember() ||
                        $event->hasRestriction(Event::RESTRICTION_CADET) && $user->isCadet())) {
                        return false;
                    }
                }
                // specific simulator on this event ?
                if ($event->getSimDcs() || $event->getSimBms()) {
                    if (!($event->getSimDcs() && $user->getSimDcs() ||
                        $event->getSimBms() && $user->getSimBms())) {
                        return false;
                    }
                }
                // specific map on this event ?
                if (null !== $event->getMap()) {
                    if (!$user->hasModule($event->getMap())) {
                        return false;
                    }
                }

                return true;
        }

        return false;
    }
}
