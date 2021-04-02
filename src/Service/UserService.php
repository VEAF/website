<?php

namespace App\Service;

use App\Component\Profile\Notification;
use App\Entity\Recruitment\Event;
use App\Entity\User;
use App\Manager\Recruitment\EventManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private EntityManager $entityManager;
    private EventManager $eventManager;

    /** @var Notification[]|array by user id */
    private $cacheNotifications = [];

    /**
     * Website (veaf / 51eg)
     * @var string
     */
    private string $website;

    public function __construct(EntityManagerInterface $entityManager, EventManager $eventManager, string $website)
    {
        $this->entityManager = $entityManager;
        $this->eventManager = $eventManager;
        $this->website = $website;
    }

    /**
     * Get all notifications for an user (with cache).
     *
     * @return Notification[]|array
     */
    public function getProfileNotifications(User $user): array
    {
        if (!isset($this->cacheNotifications[$user->getId()])) {
            $notifications = [];

            if ('veaf' === $this->website) {
                if (!$user->getSimDcs() && !$user->getSimBms()) {
                    $notifications[] = Notification::noSim('Je dois renseigner au moins un simulateur');
                }

                if (!$user->getForum()) {
                    $notifications[] = new Notification(Notification::TYPE_NO_SIM, 'Je n\'ai pas encore renseigné mon pseudo du forum');
                }

                if (!$user->getDiscord()) {
                    $notifications[] = new Notification(Notification::TYPE_NO_SIM, 'Je n\'ai pas encore renseigné mon pseudo discord');
                }
            }

            // unkown user
            if (User::STATUS_UNKNOWN === $user->getStatus()) {
                $notifications[] = Notification::noEvents('Je n\'ai pas encore postulé pour devenir cadet');
            }

            $this->cacheNotifications[$user->getId()] = $notifications;
        }

        return $this->cacheNotifications[$user->getId()];
    }

    public function countProfileNotifications(User $user, int $type = null): int
    {
        $count = 0;

        foreach ($this->getProfileNotifications($user) as $notification) {
            if (null === $type || $notification->getType() === $type) {
                ++$count;
            }
        }

        return $count;
    }

    public function hasProfileNotifications(User $user): bool
    {
        return count($this->getProfileNotifications($user)) > 0;
    }

    public function hasProfileNotification(User $user, int $type): bool
    {
        foreach ($this->getProfileNotifications($user) as $notification) {
            if ($type === $notification->getType()) {
                return true;
            }
        }

        return false;
    }

    public function markPresentation(User $user, User $recruiter): void
    {
        $event = new Event();
        $event->setUser($user);
        $event->setValidator($recruiter);
        $event->setType(Event::TYPE_PRESENTATION);

        $this->eventManager->save($event);

        $user->setNeedPresentation(false);
    }
}
