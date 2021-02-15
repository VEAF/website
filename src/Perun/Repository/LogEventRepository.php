<?php

namespace App\Perun\Repository;

use App\Perun\Entity\LogChat;
use App\Perun\Entity\LogEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogEvent[]    findAll()
 * @method LogEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogEvent::class);
    }
}
