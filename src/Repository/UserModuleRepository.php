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

    /**
     * @return UserModule[]
     */
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
    public function findByUserIndexedByModule(User $user, ?bool $active = null, array $levels = [])
    {
        $userModules = [];

        $query = $this->createQueryBuilder('user_module')
            ->select('user_module, module')
            ->leftJoin('user_module.module', 'module')
            ->andWhere('user_module.user = :user')
            ->setParameter('user', $user);

        if (null !== $active) {
            $query->andWhere('user_module.active = :active')
                ->setParameter('active', $active);
        }

        if (count($levels) > 0) {
            $query->andWhere('user_module.level IN (:levels)')
                ->setParameter('levels', $levels);
        }

        $query
            ->addOrderBy('module.period', 'desc')
            ->addOrderBy('module.type', 'asc')
            ->addOrderBy('module.name', 'asc');

        foreach ($query->getQuery()->getResult() as $userModule) {
            $userModules[$userModule->getModule()->getId()] = $userModule;
        }

        return $userModules;
    }

    /**
     * @return UserModule[] indexed by module type and module reference
     */
    public function findByUserIndexedByTypeAndModule(User $user)
    {
        $modules = [];

        foreach ($this->findByUserIndexedByModule($user) as $userModule) {
            $type = $userModule->getModule()->getType();
            if (!isset($modules[$type])) {
                $modules[$type] = [];
            }
            $modules[$type][] = $userModule;
        }

        return $modules;
    }

    /**
     * Count module owners.
     *
     * @param int   $moduleType filter by module type
     * @param array $statuses   filter by user status
     *
     * @return array[] indexed by module id
     */
    public function countUsersByModule(int $moduleType, array $statuses): array
    {
        $rows = [];

        foreach ($this->createQueryBuilder('user_module')
                     ->select('COUNT(user_module.user) AS nb, module.id AS moduleId')
                     ->join('user_module.module', 'module')
                     ->join('user_module.user', 'user')
                     ->andWhere('module.type = :type')
                     ->setParameter('type', $moduleType)
                     ->andWhere('user.status IN (:statuses)')
                     ->setParameter('statuses', $statuses)
                     ->groupBy('moduleId')
                     ->getQuery()
                     ->getArrayResult() as $row) {
            $rows[$row['moduleId']] = $row['nb'];
        }

        return $rows;
    }
}
