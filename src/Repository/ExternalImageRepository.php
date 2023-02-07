<?php

namespace App\Repository;

use App\Entity\ExternalImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExternalImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExternalImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalImage[]    findAll()
 * @method ExternalImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalImage::class);
    }

    // /**
    //  * @return ExternalImage[] Returns an array of ExternalImage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExternalImage
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
