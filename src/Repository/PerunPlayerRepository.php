<?php

namespace App\Repository;

use App\Entity\PerunPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PerunPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method PerunPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method PerunPlayer[]    findAll()
 * @method PerunPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerunPlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PerunPlayer::class);
    }

    // /**
    //  * @return PerunPlayer[] Returns an array of PerunPlayer objects
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
    public function findOneBySomeField($value): ?PerunPlayer
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
