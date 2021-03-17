<?php

namespace App\Manager\Recruitment;

use App\Entity\Recruitment\Event;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class EventManager
{
    private EntityManager $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(Event $event, bool $flush = false)
    {
        $now = new \DateTime('now');

        if (null === $event->getCreatedAt()) {
            $event->setCreatedAt(clone $now);
        }

        if (null === $event->getEventAt()) {
            $event->setEventAt(clone $now);
        }

        $event->setUpdatedAt(clone $now);
        $this->em->persist($event);

        if ($flush) {
            $this->em->flush();
        }
    }
}
