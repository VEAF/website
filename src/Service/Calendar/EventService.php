<?php

namespace App\Service\Calendar;

use App\Entity\Calendar\Choice;
use App\Entity\Calendar\Event;
use App\Entity\Calendar\Notification;
use App\Entity\User;
use App\Manager\Calendar\EventManager;
use App\Repository\Calendar\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class EventService
{
    const AUTO_CREATE_EVENT_DAYS = 32; // days between actual date and next repeatable event to be automatically created
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;

    private EventRepository $eventRepository;
    private EventManager $eventManager;

    public function __construct(EventRepository $eventRepository, EventManager $eventManager, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->eventRepository = $eventRepository;
        $this->eventManager = $eventManager;
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
     * Mark an event read for a user.
     */
    public function markEventReadByUser(Event $event, User $user, bool $flush = true): Notification
    {
        $notification = $this->entityManager->getRepository(Notification::class)->findOneBy(['event' => $event, 'user' => $user]);
        if (null === $notification) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setEvent($event);
            $notification->setReadAt(new \DateTime('now'));
            $this->entityManager->persist($notification);
            if ($flush) {
                $this->entityManager->flush($notification);
            }
        }

        return $notification;
    }

    public function findUserChoices(Event $event, User $user): array
    {
        return $this->entityManager->getRepository(Choice::class)->findBy(['event' => $event, 'user' => $user], ['priority' => 'ASC']);
    }

    public function markAllUnreadEventsAsRead(User $user)
    {
        $events = $this->entityManager->getRepository(Event::class)->findUnreadEventsByUser($user);

        foreach ($events as $event) {
            $this->markEventReadByUser($event, $user, false);
        }

        $this->entityManager->flush();
    }

    public function isNeededToCreateNextEvent(Event $event): bool
    {
        $now = new \DateTime('now');
        $nextEventDate = $this->getNextEventDateTime($event);

        // no next event date ?
        if (null == $nextEventDate) {
            return false;
        }

        $interval = $now->diff($nextEventDate);

        // need to create next event if next date is less than AUTO_CREATE_EVENT_DAYS from now
        return $interval->days <= self::AUTO_CREATE_EVENT_DAYS;
    }

    public function getNextEventDateTime(Event $event): ?\DateTime
    {
        switch ($event->getRepeatEvent()) {
            case Event::REPEAT_DAY_OF_WEEK:
                return (clone $event->getStartDate())->modify('+1 week');
            case Event::REPEAT_DAY_OF_MONTH:
                return (clone $event->getStartDate())->modify('+1 month');
            case Event::REPEAT_NTH_WEEK_DAY_OF_MONTH:
                $dayOfWeek = $event->getStartDate()->format('l');
                $dayOfMonth = $event->getStartDate()->format('d');
                $nthDayOfMonth = floor(($dayOfMonth - 1) / 7);
                $nthDay = ['first', 'second', 'third', 'fourth', 'last'];

                $newDate = (clone $event->getStartDate())->modify(sprintf('%s %s of next month', $nthDay[$nthDayOfMonth], $dayOfWeek))
                    ->setTime($event->getStartDate()->format('H'), $event->getStartDate()->format('i'));

                return $newDate;
            default:
                return null;
        }
    }

    public function createNextEvent(Event $originalEvent): Event
    {
        try {
            $nextEventDate = $this->getNextEventDateTime($originalEvent);
            if (null === $nextEventDate) {
                throw new \InvalidArgumentException('next event date can\'t be calculated from originalEvent');
            }

            $newEvent = clone $originalEvent;
            $newEvent->setDebrief(null); // clear briefing
            $newEvent->setStartDate($nextEventDate);
            $period = $originalEvent->getStartDate()->diff($originalEvent->getEndDate());
            $newEvent->setEndDate((clone $nextEventDate)->add($period));

            $originalEvent->setRepeatEvent(Event::REPEAT_NONE); // disable automatic creation for first event

            $this->eventManager->save($newEvent, true);

            return $newEvent;
        } catch (\Exception $e) {
            throw new \Exception('createNextEvent failed', 0, $e);
        }
    }
}
