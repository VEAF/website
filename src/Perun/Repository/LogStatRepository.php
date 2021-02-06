<?php

namespace App\Perun\Repository;

use App\Perun\Entity\logStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method logStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method logStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method logStat[]    findAll()
 * @method logStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogStatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, logStat::class);
    }
}
