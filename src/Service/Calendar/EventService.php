<?php

namespace App\Service\Calendar;

use App\Entity\Calendar\Event;
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
     * Load events as array for Javascript fullcalendar plugin
     *
     * @return array
     */
    public function findAsArray()
    {
        $events = [];

        foreach ($this->entityManager->getRepository(Event::class)->findBy(['deleted' => false]) as $event) {
            /** @var Event $event */
            $events[] = [
                'title' => $event->getTitle(),
                'start' => $event->getStartDate()->format('Y-m-d\TH:i:s'),
                'end' => $event->getEndDate()->format('Y-m-d\TH:i:s'),
                'url' => $this->router->generate('calendar_view', ['event' => $event->getId()]),
                'backgroundColor'=> $event->getTypeColor(),
            ];
        }

        return $events;
    }
}
