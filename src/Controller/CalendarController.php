<?php

namespace App\Controller;

use App\Entity\Calendar\Event;
use App\Entity\Calendar\Vote;
use App\Entity\UserModule;
use App\Form\CalendarEventType;
use App\Manager\Calendar\EventManager;
use App\Manager\Calendar\VoteManager;
use App\Service\Calendar\EventService;
use App\Service\FileUploaderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/calendar")
 */
class CalendarController extends AbstractController
{
    /**
     * @Route("/browse/{month}", name="calendar")
     * @ParamConverter("month", options={"format": "!Y-m"})
     */
    public function index(EventService $eventService, \DateTime $month = null): Response
    {
        $now = new \DateTime('now');
        if (null === $month) {
            $month = clone $now;
        }

        return $this->render('calendar/index.html.twig', [
            'now' => new \DateTime('now'),
            'month' => $month,
            'events' => $eventService->findAsArray($this->getUser()),
        ]);
    }

    /**
     * @Route("/add/{periodString}", name="calendar_add")
     * @Route("/edit/{event}", name="calendar_edit")
     * @Security("is_granted('EVENT_ADD') or is_granted('EDIT', event)")
     */
    public function edit(FileUploaderService $uploaderService, Request $request, EventManager $eventManager, Event $event = null, string $periodString = null): Response
    {
        if (null === $event) {
            $event = new Event();

            $date = (new \DateTime($periodString))->setTime(21, 00);
            $event->setStartDate($date);
            $event->setEndDate((clone $date)->setTime(23, 00));
            $event->setOwner($this->getUser());
        }

        $form = $this->createForm(CalendarEventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $uploadedImage */
            $uploadedImage = $form->get('image')->getData();
            if ($uploadedImage) {
                $imageHeader = $uploaderService->upload($uploadedImage, $this->getUser());
                $event->setImage($imageHeader);
            }

            $eventManager->save($event, true);
            $this->addFlash('success', 'L\'événement a été enregistré');

            return $this->redirectToRoute('calendar_view', ['event' => $event->getId()]);
        }

        return $this->render('calendar/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{event}/vote/{vote}", name="calendar_vote")
     * @ParamConverter("month", options={"format": "!Y-m"})
     * @Security("is_granted('VOTE', event)")
     */
    public function vote(VoteManager $voteManager, Event $event, string $vote): Response
    {
        $eventvote = $this->getDoctrine()->getRepository(Vote::class)->findOneBy(['event' => $event, 'user' => $this->getUser()]);
        if (null === $eventvote) {
            $eventvote = new Vote();
            $eventvote->setCreatedAt(new \DateTime('now'));
            $eventvote->setUser($this->getUser());
            $eventvote->setEvent($event);
        }
        switch ($vote) {
            case 'yes':
                $eventvote->setVote(true);
                break;
            case 'no':
                $eventvote->setVote(false);
                break;
            case 'perhaps':
            default:
                $eventvote->setVote(null);
                break;
        }

        $voteManager->save($eventvote, true);
        $this->addFlash('success', 'Le vote a été enregistré');

        return $this->redirectToRoute('calendar_view', ['event' => $event->getId()]);
    }

    /**
     * @Route("/{event}", name="calendar_view")
     * @ParamConverter("month", options={"format": "!Y-m"})
     */
    public function view(Event $event, EventService $eventService): Response
    {
        if ($event->isDeleted()) {
            throw new NotFoundHttpException('event not found');
        }

        $vote = $this->getDoctrine()->getRepository(Vote::class)->findOneBy(['event' => $event, 'user' => $this->getUser()]);
        if (null !== $this->getUser()) {
            $eventService->markEventReadByUser($event, $this->getUser());
        }

        return $this->render('calendar/view.html.twig', [
            'event' => $event,
            'userVote' => $vote,
            'modules' => null !== $this->getUser() ? $this->getDoctrine()->getRepository(UserModule::class)->findBy(['user' => $this->getUser()]) : [],
        ]);
    }
}
