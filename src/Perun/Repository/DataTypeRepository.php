<?php

namespace App\Perun\Repository;

use App\DTO\DataTypeStat;
use App\DTO\TimeInterval;
use App\Entity\Module;
use App\Entity\Player;
use App\Perun\Entity\DataType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataType[]    findAll()
 * @method DataType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataType::class);
    }

    /**
     * Count totals by data type.
     *
     * @return DataTypeStat[]|array
     */
    public function countTotalsByType(?Player $player, /*int $moduleType,*/ TimeInterval $period = null): array
    {
        $query = $this->createQueryBuilder('t')
            ->select('t,
             SUM(l.time)/60 AS time,
             SUM(l.pvp) AS pvp,
             SUM(l.deaths) AS deaths,
             SUM(l.ejections) AS ejections,
             SUM(l.crashes) AS crashes,
             SUM(l.teamKills) AS teamKills,
             SUM(l.killsPlanes) AS killsPlanes,
             SUM(l.killsHelicopters) AS killsHelicopters,
             SUM(l.killsAirDefense) AS killsAirDefense,
             SUM(l.killsArmor) AS killsArmor,
             SUM(l.killsUnarmed) AS killsUnarmed,
             SUM(l.killsInfantry) AS killsInfantry,
             SUM(l.killsFortification) AS killsFortification,
             SUM(l.killsArtillery) AS killsArtillery,
             SUM(l.killsOther) AS killsOther,
             SUM(l.airfieldTakeoffs) AS airfieldTakeoffs,
             SUM(l.airfieldLandings) AS airfieldLandings,
             SUM(l.shipTakeoffs) AS shipTakeoffs,
             SUM(l.shipLandings) AS shipLandings,
             SUM(l.farpTakeoffs) AS farpTakeoffs,
             SUM(l.farpLandings) AS farpLandings,
             SUM(l.otherTakeoffs) AS otherTakeoffs,
             SUM(l.otherLandings) AS otherLandings
            ')
            ->innerJoin('App\Perun\Entity\LogStat', 'l', 'WITH', 'l.type = t')
            ->join('l.player', 'p')
            ->andWhere('p.ucid = :ucid')
            ->setParameter('ucid', $player->getUcid())
            ->andWhere('t.name NOT IN (:excludeTypes)')
            ->setParameter('excludeTypes', ['?_-1'])
            ->groupBy('t');

        if (null !== $period) {
            if (null !== $period->getStart()) {
                $query->andWhere('l.datetime >= :start')
                    ->setParameter('start', $period->getStart());
            }
            if (null !== $period->getEnd()) {
                $query->andWhere('l.datetime <= :end')
                    ->setParameter('end', $period->getEnd());
            }
        }

        $results = [];

        foreach ($query->getQuery()->getResult() as $row) {
            $stat = new DataTypeStat();
            $stat->setDataType($row[0]);
            $stat->setFromRow($row);

            $results[] = $stat;
        }

        return $results;
    }

    public function findOneByPlayerAndBestTotalHours(Player $player, int $moduleType, TimeInterval $period = null): ?DataTypeStat
    {
        switch ($moduleType) {
            case Module::TYPE_AIRCRAFT:
                $types = DataType::AIRCRAFT_TYPES;
                break;
            case Module::TYPE_HELICOPTER:
                $types = DataType::HELICOPTERS_TYPES;
                break;
            default:
                $types = [];
                break;
        }

        $query = $this->createQueryBuilder('t')
            ->select('t, SUM(l.time)/60 AS totalHours')
            ->innerJoin('App\Perun\Entity\LogStat', 'l', 'WITH', 'l.type = t')
            ->andWhere('t.name IN (:types)')
            ->setParameter('types', $types)
            ->join('l.player', 'p')
            ->andWhere('p.ucid = :ucid')
            ->setParameter('ucid', $player->getUcid())
            ->groupBy('t')
            ->orderBy('totalHours', 'desc')
            ->setMaxResults(1);

        if (null !== $period) {
            if (null !== $period->getStart()) {
                $query->andWhere('l.datetime >= :start')
                    ->setParameter('start', $period->getStart());
            }
            if (null !== $period->getEnd()) {
                $query->andWhere('l.datetime <= :end')
                    ->setParameter('end', $period->getEnd());
            }
        }

        $result = $query->getQuery()
            ->getOneOrNullResult();

        if (null === $result) {
            return null;
        }

        $stat = new DataTypeStat();
        $stat->setDataType($result[0]);
        $stat->setTime($result['totalHours']);

        return $stat;
    }
}
