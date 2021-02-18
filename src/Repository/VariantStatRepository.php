<?php

namespace App\Repository;

use App\Entity\VariantStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VariantStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method VariantStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method VariantStat[]    findAll()
 * @method VariantStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VariantStatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VariantStat::class);
    }

    // /**
    //  * @return VariantStat[] Returns an array of VariantStat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VariantStat
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
