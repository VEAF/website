<?php

namespace App\Repository;

use App\Entity\Player;
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

    public function countTotalHoursByPlayer(Player $player): float
    {
        return $this->createQueryBuilder('s')
                ->select('SUM(s.total) AS total')
                ->andWhere('s.player = :player')
                ->setParameter('player', $player)
                ->getQuery()
                ->getSingleScalarResult() / 3600.0;
    }
}
