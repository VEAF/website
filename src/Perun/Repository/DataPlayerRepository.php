<?php

namespace App\Perun\Repository;

use App\Perun\Entity\DataPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataPlayer[]    findAll()
 * @method DataPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataPlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataPlayer::class);
    }

}
