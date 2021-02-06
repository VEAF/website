<?php

namespace App\Perun\Repository;

use App\Perun\Entity\LogChat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LogChat|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogChat|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogChat[]    findAll()
 * @method LogChat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogChatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogChat::class);
    }
}
