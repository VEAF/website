<?php

namespace App\Security\Voter\Page;

use App\Entity\Calendar\Event;
use App\Entity\Menu\Item;
use App\Entity\User;
use App\Security\Restriction;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MenuItemVoter extends Voter
{
    const VIEW = 'VIEW';
    private Restriction $restriction;

    public function __construct(Restriction $restriction)
    {
        $this->restriction = $restriction;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW]) && $subject instanceof Item;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (Item::class !== get_class($subject)) {
            return false;
        }

        /** @var Item $item */
        $item = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->restriction->isGrantedToLevel($user, $item->getRestriction());
        }

        return false;
    }
}
