<?php

namespace App\Repository;

use App\Entity\ModuleSystem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuleSystem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleSystem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleSystem[]    findAll()
 * @method ModuleSystem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleSystemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleSystem::class);
    }

    // /**
    //  * @return ModuleSystem[] Returns an array of ModuleSystem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModuleSystem
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
