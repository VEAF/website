<?php

namespace App\Repository\Calendar;

use App\Entity\Calendar\EventSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventSlot[]    findAll()
 * @method EventSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventSlot::class);
    }

    // /**
    //  * @return EventSlot[] Returns an array of EventSlot objects
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
    public function findOneBySomeField($value): ?EventSlot
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
