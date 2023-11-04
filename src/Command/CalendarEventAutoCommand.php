<?php

namespace App\Command;

use App\Repository\Calendar\EventRepository;
use App\Service\Calendar\EventService;
use App\Service\TeamSpeak3ClientCache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalendarEventAutoCommand extends Command
{
    protected static $defaultName = 'app:calendar:event:auto';

    private EventService $eventService;
    private EventRepository $eventRepository;

    public function __construct(EventService $eventService, EventRepository $eventRepository)
    {
        $this->eventService = $eventService;
        $this->eventRepository = $eventRepository;
        parent::__construct(static::$defaultName);
    }

    protected function configure()
    {
        $this->setDescription('Auto create next repeatable events from Calendar');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('scanning repeatable events');

        $newEvents = 0;
        foreach ($this->eventRepository->findAllRepeatableEvents() as $event) {
            if ($this->eventService->isNeededToCreateNextEvent($event)) {
                $output->writeln(sprintf('creating new event from event id=%d,type=%s,repeat=%s: %s', $event->getId(), $event->getTypeAsString(), $event->getRepeatEventAsCode(), substr($event->getTitle(), 0, 30)));
                $newEvent = $this->eventService->createNextEvent($event);
                $output->writeln(sprintf('new event: id=%d,type=%s,repeat=%s: %s', $newEvent->getId(), $newEvent->getTypeAsString(), $newEvent->getRepeatEventAsCode(), substr($newEvent->getTitle(), 0, 30)));
                $newEvents++;
            }
        }

        $output->writeln(sprintf('scanning repeatable events done (%d new events).', $newEvents));

        return 0;
    }
}
