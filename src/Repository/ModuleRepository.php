<?php

namespace App\Repository;

use App\DTO\ModuleStat;
use App\Entity\Module;
use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Module|null find($id, $lockMode = null, $lockVersion = null)
 * @method Module|null findOneBy(array $criteria, array $orderBy = null)
 * @method Module[]    findAll()
 * @method Module[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    public function findOneByPlayerAndBestTotalHours(Player $player, int $moduleType): ?ModuleStat
    {
        $result = $this->createQueryBuilder('m')
            ->select('m, SUM(s.total) AS totalHours')
            ->join('m.variants', 'v')
            ->join('v.stats', 's')
            ->andWhere('m.type = :type')
            ->setParameter('type', $moduleType)
            ->andWhere('s.player = :player')
            ->setParameter('player', $player)
            ->groupBy('m')
            ->orderBy('totalHours', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $result) {
            return null;
        }

        $stat = new ModuleStat();
        $stat->setModule($result[0]);
        $stat->setTotalHours($result['totalHours'] / 3600);

        return $stat;
    }
}
