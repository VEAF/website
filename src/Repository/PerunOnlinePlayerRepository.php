<?php

namespace App\Repository;

use App\Entity\PerunInstance;
use App\Entity\PerunOnlinePlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PerunOnlinePlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PerunOnlinePlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PerunOnlinePlayer[]    findAll()
 * @method PerunOnlinePlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerunOnlinePlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PerunOnlinePlayer::class);
    }

    /**
     * Fetch players by instance, ignoring with 0 ping
     *
     * @return PerunOnlinePlayer[]
     */
    public function findRealPlayersByInstance(PerunInstance $instance)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.instance = :instance')
            ->setParameter('instance', $instance)
            ->andWhere('p.ping > 0')
            ->addOrderBy('p.side', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
