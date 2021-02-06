<?php

namespace App\Perun\Repository;

use App\Perun\Entity\Loglogin;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Loglogin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Loglogin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Loglogin[]    findAll()
 * @method Loglogin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogLoginRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Loglogin::class);
    }
}
