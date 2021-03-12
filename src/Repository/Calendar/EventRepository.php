<?php

namespace App\Repository\Calendar;

use App\Entity\Calendar\Event;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Count new events for an user (if no user, count all events) from now to the next end of month.
     */
    public function countNewEventsByUser(?User $user): int
    {
        $start = new \DateTime('now');
        $end = (new \DateTime($start->format('Y/m/01 23:59:59')))->modify('+2 month')->modify('-1 day');

        $query = $this->createQueryBuilder('e')
            ->select('COUNT(e.id) AS nb')
            ->andWhere('e.deleted = 0');
        if (null !== $user) {
            $query->leftJoin('e.notifications', 'n')
                ->leftJoin('n.user', 'u', 'WITH', 'n.user = :user')
                ->andWhere('n IS NULL')
                ->setParameter('user', $user); //->having('IS_NULL(n)');
        }

        return $query->andWhere('e.endDate >= :start')
            ->setParameter('start', $start)
            ->andWhere('e.endDate < :end')
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Count next events (in next $days).
     */
    public function countNextEvents(int $days = 7): int
    {
        $start = new \DateTime('now');
        $end = (new \DateTime($start->format('Y/m/d 23:59:59')))->modify(sprintf('+%d days', $days));

        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id) AS nb')
            ->andWhere('e.deleted = 0')
            ->andWhere('e.endDate >= :start')
            ->setParameter('start', $start)
            ->andWhere('e.endDate < :end')
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Count new events for an user (if no user, count all events) from now to the next end of month.
     *
     * @return int
     */
    public function findNotDeletedWithNotificationStatus(?User $user)
    {
        $query = $this->createQueryBuilder('e')
            ->select('e')
            ->andWhere('e.deleted = 0');
        if (null !== $user) {
            $query->addSelect('COUNT(n) AS notifications')
                ->leftJoin('e.notifications', 'n')
                ->leftJoin('n.user', 'u', 'WITH', 'n.user = :user')
                ->setParameter('user', $user);
        } else {
            $query->addSelect('0 AS notifications');
        }
        $query->groupBy('e');

        return $query->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?CalendarEvent
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
