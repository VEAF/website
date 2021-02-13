<?php

namespace App\Repository;

use App\Entity\Module;
use App\Entity\User;
use App\Entity\UserModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserModule[]    findAll()
 * @method UserModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserModule::class);
    }

    public function findByModuleAndUserStatus(Module $module, array $statuses)
    {
        return $this->createQueryBuilder('user_module')
            ->select('user_module, user')
            ->leftJoin('user_module.user', 'user')
            ->andWhere('user_module.module = :module')
            ->setParameter('module', $module)
            ->andWhere('user.status IN (:statuses)')
            ->setParameter('statuses', $statuses)
            ->orderBy('user.nickname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return UserModule[] indexed by module reference
     */
    public function findByUserIndexedByModule(User $user)
    {
        $userModules = [];
        foreach ($this->createQueryBuilder('user_module')
                     ->select('user_module, module')
                     ->leftJoin('user_module.module', 'module')
                     ->andWhere('user_module.user = :user')
                     ->setParameter('user', $user)
                     ->getQuery()
                     ->getResult() as $userModule) {
            $userModules[$userModule->getModule()->getId()] = $userModule;
        }

        return $userModules;
    }
}
