<?php

namespace App\Repository;

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

    // /**
    //  * @return PerunOnlinePlayer[] Returns an array of PerunOnlinePlayer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PerunOnlinePlayer
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
