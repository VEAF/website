<?php

namespace App\Manager\Calendar;

use App\Entity\Calendar\Event;
use App\Entity\User;
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
            $event->setCreatedAt($now);
        }

        $event->setUpdatedAt($now);
        $this->em->persist($event);

        if ($flush) {
            $this->em->flush();
        }
    }

    public function delete(Event $event, bool $flush = false)
    {
        $event->setDeleted(true);
        $event->setDeletedAt(new \DateTime('now'));

        if ($flush) {
            $this->em->flush();
        }
    }
}
