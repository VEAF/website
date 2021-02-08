<?php

namespace App\Manager;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private EntityManager $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(User $user, bool $markUpdated = true, bool $flush = false)
    {
        $this->em->persist($user);

        if (null === $user->getCreatedAt()) {
            $user->setCreatedAt(new \DateTime('now'));
        }
        if ($markUpdated) {
            $user->setUpdatedAt(new \DateTime('now'));
        }

        if ($flush) {
            $this->em->flush();
        }
    }

}
