<?php

namespace App\Perun\Repository;

use App\Perun\Entity\Instance;
use App\Perun\Entity\OnlinePlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OnlinePlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method OnlinePlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method OnlinePlayer[]    findAll()
 * @method OnlinePlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OnlinePlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OnlinePlayer::class);
    }

    /**
     * Fetch players by instance, ignoring with 0 ping.
     *
     * @return OnlinePlayer[]
     */
    public function findRealPlayersByInstance(Instance $instance)
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
