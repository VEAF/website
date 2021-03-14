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

    /**
     * Count totals by module.
     *
     * @return ModuleStat[]|array
     */
    public function countTotalsByModule(?Player $player): array
    {
        $query = $this->createQueryBuilder('module')
            ->select('module, NEW App\DTO\ModuleStat(
            SUM(s.total)/3600.0,
            SUM(s.inAir)/3600.0,
            SUM(s.killsGroundUnitsTotal),
            SUM(s.killsBuildingsTotal),
            SUM(s.killsPlanesTotal), 
            SUM(s.landingTotal), 
            SUM(s.takeoffTotal), 
            SUM(s.lossesPilotDeath), 
            SUM(s.lossesEject), 
            SUM(s.lossesCrash)
            )')
            ->join('module.variants', 'variant')
            ->join('variant.stats', 's')
            ->groupBy('module');

        if (null !== $player) {
            $query->andWhere('s.player = :player')->setParameter('player', $player);
        }

        return $query->getQuery()->getResult();
    }
}
