<?php

namespace App\EventSubscriber;

use App\Entity\Recruitment\Event;
use App\Entity\User;
use App\Manager\Recruitment\EventManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\GuardEvent;
use Symfony\Component\Workflow\TransitionBlocker;
use Symfony\Component\Workflow\Event\Event as WorkflowEvent;

class RecruitmentSubscriber implements EventSubscriberInterface
{
    private EventManager $eventManager;

    public function __construct(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    public function guardPromote(GuardEvent $event)
    {
        // @todo
        // handle restrictions
        $event->addTransitionBlocker(new TransitionBlocker('feature WIP', '0'));
    }

    public function enterCadet(WorkflowEvent $workflowEvent)
    {
        $event = new Event();

        /** @var User $user */
        $user = $workflowEvent->getSubject();

        $event->setUser($user);
        $event->setType(Event::TYPE_TO_APPLY);
        $user->setNeedPresentation(true);

        $this->eventManager->save($event);
    }

    public function enterGuest(WorkflowEvent $workflowEvent)
    {
        $event = new Event();

        /** @var User $user */
        $user = $workflowEvent->getSubject();

        $event->setUser($user);
        $event->setType(Event::TYPE_GUEST);

        $this->eventManager->save($event);
    }

    public function onPromote(GuardEvent $event)
    {
        // @todo
        // handle restrictions
        $event->addTransitionBlocker(new TransitionBlocker('feature WIP', '0'));
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.recruitment.enter.cadet' => ['enterCadet'],
            'workflow.recruitment.enter.guest' => ['enterGuest'],
            'workflow.recruitment.enter.promote' => ['onPromote'],
            'workflow.recruitment.guard.promote' => ['guardPromote'],
        ];
    }
}