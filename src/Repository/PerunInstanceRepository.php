<?php

namespace App\Repository;

use App\Entity\PerunInstance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PerunInstance|null find($id, $lockMode = null, $lockVersion = null)
 * @method PerunInstance|null findOneBy(array $criteria, array $orderBy = null)
 * @method PerunInstance[]    findAll()
 * @method PerunInstance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PerunInstanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PerunInstance::class);
    }

    // /**
    //  * @return PerunInstance[] Returns an array of PerunInstance objects
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
    public function findOneBySomeField($value): ?PerunInstance
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
