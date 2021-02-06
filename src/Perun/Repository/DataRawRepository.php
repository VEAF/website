<?php

namespace App\Perun\Repository;

use App\Perun\Entity\DataRaw;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataRaw|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataRaw|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataRaw[]    findAll()
 * @method DataRaw[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataRawRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataRaw::class);
    }
}
