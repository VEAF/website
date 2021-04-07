<?php

namespace App\Manager\Calendar;

use App\Entity\Calendar\Choice;
use App\Entity\Calendar\Vote;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ChoiceManager
{
    private EntityManager $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(Choice $choice, bool $flush = false)
    {
        $now = new \DateTime('now');

        if (null === $choice->getCreatedAt()) {
            $choice->setCreatedAt($now);
        }

        $choice->setUpdatedAt($now);
        $this->em->persist($choice);

        if ($flush) {
            $this->em->flush();
        }
    }
}
