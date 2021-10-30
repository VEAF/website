<?php

namespace App\Repository;

use App\Entity\ModuleRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModuleRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModuleRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModuleRole[]    findAll()
 * @method ModuleRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModuleRole::class);
    }

    // /**
    //  * @return ModuleRole[] Returns an array of ModuleRole objects
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
    public function findOneBySomeField($value): ?ModuleRole
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
