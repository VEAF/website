<?php

namespace App\Service\Calendar;

use App\Entity\Calendar\Choice;
use App\Entity\Calendar\Event;
use App\Entity\Calendar\Notification;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class EventService
{
    private EntityManager $entityManager;
    private Router $router;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * Load events as array for Javascript fullcalendar plugin for an optional user.
     *
     * @return array
     */
    public function findAsArray(?User $user)
    {
        $events = [];

        foreach ($this->entityManager->getRepository(Event::class)->findNotDeletedWithNotificationStatus($user) as $eventRow) {
            $event = $eventRow[0];
            $notified = (null === $user || $eventRow['notifications'] > 0);
            /* @var Event $event */
            $events[] = [
                'title' => ($notified ? '' : '* ').$event->getTitle(),
                'start' => $event->getStartDate()->format('Y-m-d\TH:i:s'),
                'end' => $event->getEndDate()->format('Y-m-d\TH:i:s'),
                'url' => $this->router->generate('calendar_view', ['event' => $event->getId()]),
                'backgroundColor' => $event->getTypeColor(),
            ];
        }

        return $events;
    }

    /**
     * Mark an event read for an user.
     */
    public function markEventReadByUser(Event $event, User $user): Notification
    {
        $notification = $this->entityManager->getRepository(Notification::class)->findOneBy(['event' => $event, 'user' => $user]);
        if (null === $notification) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setEvent($event);
            $notification->setReadAt(new \DateTime('now'));
            $this->entityManager->persist($notification);
            $this->entityManager->flush($notification);
        }

        return $notification;
    }

    public function findUserChoices(Event $event, User $user): array
    {
        return $this->entityManager->getRepository(Choice::class)->findBy(['event' => $event, 'user' => $user], ['priority' => 'ASC']);
    }
}
