<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    const ZOMBIE_DAYS = 31;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findByUserStatus(array $statuses)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->andWhere('user.status IN (:statuses)')
            ->setParameter('statuses', $statuses)
            ->orderBy('user.nickname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countCadetsWaitingForPresentation(array $statuses)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->andWhere('user.status = :status)')
            ->setParameter('status', User::STATUS_CADET)
            ->orderBy('user.nickname', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return User[]
     */
    public function findZombies(): array
    {
        /** @var User[] $cadets */
        $cadets = $this->createQueryBuilder('user')
            ->select('user')
            ->andWhere('user.status = :status')
            ->setParameter('status', User::STATUS_CADET)
            ->orderBy('user.nickname', 'ASC')
            ->getQuery()
            ->getResult();

        $zombies = [];
        foreach ($cadets as $cadet) {
            foreach ($cadet->getRecruitmentEvents() as $event) {
                if ((time() - $event->getCreatedAt()->getTimestamp()) < self::ZOMBIE_DAYS * 24 * 3600) {
                    continue 2;
                }
            }
            // no events in last days ? it's a zombie
            $zombies[] = $cadet;
        }

        return $zombies;
    }
}
