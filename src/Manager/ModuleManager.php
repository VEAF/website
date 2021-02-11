<?php

namespace App\Manager;

use App\Entity\Module;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ModuleManager
{
    private EntityManager $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function save(Module $module, bool $flush = false)
    {
        $this->em->persist($module);

        if ($flush) {
            $this->em->flush();
        }
    }
}
