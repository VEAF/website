<?php

namespace App\Twig;

use App\Entity\Calendar\Event;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class EventExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('event_type', [$this, 'eventType']),
            new TwigFilter('event_restriction', [$this, 'eventRestriction']),
        ];
    }

    public function eventType(?Event $event)
    {
        if (null === $event) {
            return 'inconnu';
        }

        return $event->getTypeAsString();
    }

    public function eventRestriction(int $restriction)
    {
        if (isset($event::RESTRICTIONS[$restriction])) {
            return $event::RESTRICTIONS[$restriction];
        }

        return 'inconnue';
    }
}
