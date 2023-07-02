<?php

namespace App\Perun\Repository;

use App\DTO\DataPlayerStat;
use App\DTO\TimeInterval;
use App\Entity\Player;
use App\Perun\Entity\LogStat;
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
        parent::__construct($registry, LogStat::class);
    }

    public function countTotals(?Player $player, TimeInterval $period = null): DataPlayerStat
    {
        $query = $this->createQueryBuilder('l')
            ->select('NEW App\DTO\DataPlayerStat(
            IFNULL(SUM(l.time)/60.0,0),
            IFNULL(SUM(l.pvp),0),
            IFNULL(SUM(l.deaths),0),
            IFNULL(SUM(l.ejections),0),
            IFNULL(SUM(l.crashes),0),
            IFNULL(SUM(l.teamKills),0),
            IFNULL(SUM(l.killsPlanes),0),
            IFNULL(SUM(l.killsHelicopters),0),
            IFNULL(SUM(l.killsAirDefense),0),
            IFNULL(SUM(l.killsArmor),0),
            IFNULL(SUM(l.killsUnarmed),0),
            IFNULL(SUM(l.killsInfantry),0),
            IFNULL(SUM(l.killsFortification),0),
            IFNULL(SUM(l.killsArtillery),0),
            IFNULL(SUM(l.killsOther),0),
            IFNULL(SUM(l.airfieldTakeoffs),0),
            IFNULL(SUM(l.airfieldLandings),0),
            IFNULL(SUM(l.shipTakeoffs),0),
            IFNULL(SUM(l.shipLandings),0),
            IFNULL(SUM(l.farpTakeoffs),0),
            IFNULL(SUM(l.farpLandings),0),
            IFNULL(SUM(l.otherTakeoffs),0),
            IFNULL(SUM(l.otherLandings),0)
            )');

        if (null !== $player) {
            $query->innerJoin('l.player', 'dataPlayer', 'l.player = dataPlayer');
            $query->andWhere('dataPlayer.ucid = :ucid')->setParameter('ucid', $player->getUcid());
        }

        if(null !== $period) {
            if(null !== $period->getStart()) {
                $query->andWhere('l.datetime >= :start')
                    ->setParameter('start', $period->getStart());
            }
            if(null !== $period->getEnd()) {
                $query->andWhere('l.datetime <= :end')
                    ->setParameter('end', $period->getEnd());
            }
        }

        return $query->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()->setPlayer($player);
    }

}
