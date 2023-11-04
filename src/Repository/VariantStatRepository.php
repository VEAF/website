<?php

namespace App\Repository;

use App\DTO\ModuleStat;
use App\DTO\VariantStat as VariantStatDTO;
use App\Entity\Module;
use App\Entity\Player;
use App\Entity\Variant;
use App\Entity\VariantStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @deprecated https://github.com/VEAF/website/issues/365
 *
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

    /** @deprecated use countTotals instead */
    public function countTotalHoursByPlayer(Player $player): float
    {
        return $this->createQueryBuilder('s')
                ->select('SUM(s.total) AS total')
                ->andWhere('s.player = :player')
                ->setParameter('player', $player)
                ->getQuery()
                ->getSingleScalarResult() / 3600.0;
    }

    public function countTotals(?Player $player): ModuleStat
    {
        $query = $this->createQueryBuilder('s')
            ->select('NEW App\DTO\ModuleStat(
            IFNULL(SUM(s.total)/3600.0,0),
            IFNULL(SUM(s.inAir)/3600.0,0),
            IFNULL(SUM(s.killsGroundUnitsTotal),0),
            IFNULL(SUM(s.killsBuildingsTotal),0),
            IFNULL(SUM(s.killsPlanesTotal),0), 
            IFNULL(SUM(s.killsHelicoptersTotal),0), 
            IFNULL(SUM(s.landingTotal),0), 
            IFNULL(SUM(s.takeoffTotal),0), 
            IFNULL(SUM(s.lossesPilotDeath),0), 
            IFNULL(SUM(s.lossesEject),0), 
            IFNULL(SUM(s.lossesCrash),0)
            )');

        if (null !== $player) {
            $query->andWhere('s.player = :player')->setParameter('player', $player);
        }

        return $query->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * Count totals by module.
     *
     * @return ModuleStat[]|array
     */
    public function countTotalsByModule(?Player $player): array
    {
        $query = $this->createQueryBuilder('s')
            ->select('module.id AS module_id,
            SUM(s.total)/3600.0 AS total,
            SUM(s.inAir)/3600.0 AS inAir,
            SUM(s.killsGroundUnitsTotal) AS killsGroundUnitsTotal,
            SUM(s.killsBuildingsTotal) AS killsBuildingsTotal,
            SUM(s.killsPlanesTotal) AS killsPlanesTotal, 
            SUM(s.killsHelicoptersTotal) AS killsHelicoptersTotal, 
            SUM(s.killsShipsTotal) AS killsShipsTotal, 
            SUM(s.landingTotal) AS landingTotal, 
            SUM(s.takeoffTotal) AS takeoffTotal, 
            SUM(s.lossesPilotDeath) AS lossesPilotDeath, 
            SUM(s.lossesEject) AS lossesEject, 
            SUM(s.lossesCrash) AS lossesCrash
            ')
            ->join('s.variant', 'variant')
            ->join('variant.module', 'module')
            ->groupBy('module_id');

        if (null !== $player) {
            $query->andWhere('s.player = :player')->setParameter('player', $player);
        }

        $results = [];

        foreach ($query->getQuery()->getResult() as $row) {
            $module = $this->getEntityManager()->getRepository(Module::class)->findOneById($row['module_id']);
            $stat = new ModuleStat();
            $stat->setModule($module);
            $stat->setFromRow($row);

            $results[] = $stat;
        }

        return $results;
    }

    /**
     * Count totals by variant.
     *
     * @return VariantStat[]|array
     */
    public function countTotalsByVariant(?Player $player): array
    {
        $query = $this->createQueryBuilder('s')
            ->select('variant.id AS variant_id,
            SUM(s.total)/3600.0 AS totalHours,
            SUM(s.inAir)/3600.0 AS inAirHours,
            SUM(s.killsGroundUnitsTotal) AS killsGroundUnitsTotal,
            SUM(s.killsBuildingsTotal) AS killsBuildingsTotal,
            SUM(s.killsPlanesTotal) AS killsPlanesTotal, 
            SUM(s.killsHelicoptersTotal) AS killsHelicoptersTotal, 
            SUM(s.killsShipsTotal) AS killsShipsTotal, 
            SUM(s.landingTotal) AS landingTotal, 
            SUM(s.takeoffTotal) AS takeoffTotal, 
            SUM(s.lossesPilotDeath) AS lossesPilotDeath, 
            SUM(s.lossesEject) AS lossesEject, 
            SUM(s.lossesCrash) AS lossesCrash
            ')
            ->join('s.variant', 'variant')
            ->groupBy('variant_id');

        if (null !== $player) {
            $query->andWhere('s.player = :player')->setParameter('player', $player);
        }

        $results = [];

        foreach ($query->getQuery()->getResult() as $row) {
            $variant = $this->getEntityManager()->getRepository(Variant::class)->findOneById($row['variant_id']);
            $stat = new VariantStatDTO();
            $stat->setVariant($variant);
            $stat->setFromRow($row);

            $results[] = $stat;
        }

        return $results;
    }
}
