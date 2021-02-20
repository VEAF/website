<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Player|null find($id, $lockMode = null, $lockVersion = null)
 * @method Player|null findOneBy(array $criteria, array $orderBy = null)
 * @method Player[]    findAll()
 * @method Player[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }

    public function countAutolink(): int
    {
        return $this->createQueryBuilder('player')
            ->select('count(player) AS nb')
            ->innerJoin(\App\Perun\Entity\Player::class, 'perunPlayer', 'WITH', 'perunPlayer.ucid = player.ucid')
            ->leftJoin('player.user', 'user')
            ->andWhere('user is null')
            ->getQuery()
            ->getSingleResult()['nb'];
    }

    public function findAutolink(): int
    {
        return $this->createQueryBuilder('player')
            ->innerJoin(\App\Perun\Entity\Player::class, 'perunPlayer', 'WITH', 'perunPlayer.ucid = player.ucid')
            ->leftJoin('player.user', 'user')
            ->andWhere('user is null')
            ->getQuery()
            ->getResult();
    }
}
