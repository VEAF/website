<?php

namespace App\Perun\Repository;

use App\Perun\Entity\DataMissionHash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataMissionHash|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataMissionHash|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataMissionHash[]    findAll()
 * @method DataMissionHash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataMissionHashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataMissionHash::class);
    }
}
