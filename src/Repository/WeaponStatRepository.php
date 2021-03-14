<?php

namespace App\Repository;

use App\Entity\WeaponStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WeaponStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeaponStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeaponStat[]    findAll()
 * @method WeaponStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeaponStatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeaponStat::class);
    }

}
