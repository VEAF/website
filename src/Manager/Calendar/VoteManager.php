<?php

namespace App\Manager\Calendar;

use App\Entity\Calendar\Event;
use App\Entity\Calendar\Vote;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class VoteManager
{
    private EntityManager $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(Vote $vote, bool $flush = false)
    {
        $now = new \DateTime('now');

        if (null === $vote->getCreatedAt()) {
            $vote->setCreatedAt($now);
        }

        $vote->setUpdatedAt($now);
        $this->em->persist($vote);

        if ($flush) {
            $this->em->flush();
        }
    }

}
