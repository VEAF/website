<?php

namespace App\Controller;

use App\Entity\Calendar\Choice;
use App\Entity\Calendar\Event;
use App\Entity\Calendar\Vote;
use App\Entity\UserModule;
use App\Form\CalendarChoiceType;
use App\Form\CalendarEventType;
use App\Manager\Calendar\ChoiceManager;
use App\Manager\Calendar\EventManager;
use App\Manager\Calendar\VoteManager;
use App\Repository\Calendar\EventRepository;
use App\Service\Calendar\EventService;
use App\Service\FileUploaderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
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
    public function index(EventService $eventService, \DateTime $month = null, EventRepository $eventRepository): Response
    {
        $now = new \DateTime('now');
        if (null === $month) {
            $month = clone $now;
        }

        return $this->render('calendar/index.html.twig', [
            'now' => new \DateTime('now'),
            'month' => $month,
            'events' => $eventService->findAsArray($this->getUser()),
            'myEvents' => $eventRepository->findNextEventsByUser($this->getUser()),
        ]);
    }

    /**
     * @Route("/add/{periodString}", name="calendar_add")
     * @Route("/edit/{event}", name="calendar_edit")
     */
    public function edit(FileUploaderService $uploaderService, Request $request, EventManager $eventManager, Event $event = null, string $periodString = null): Response
    {
        if (null === $event) {
            $event = new Event();

            $date = (new \DateTime($periodString))->setTime(21, 00);
            $event->setStartDate($date);
            $event->setEndDate((clone $date)->setTime(23, 00));
            $event->setOwner($this->getUser());
            $event->setRegistration(true);
        }

        // security check
        switch ($request->get('_route')) {
            case 'calendar_add':
                $this->denyAccessUnlessGranted('EVENT_ADD');
                break;
            case 'calendar_edit':
                $this->denyAccessUnlessGranted('EDIT', $event);
                break;
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
     * @Route("/copy/{event}", name="calendar_copy")
     * @Security("is_granted('EVENT_ADD')")
     */
    public function copy(Request $request, EventManager $eventManager, Event $event = null, string $periodString = null): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newEvent = clone $event;
            $newEvent->setOwner($this->getUser());
            $date = (new \DateTime($periodString))->setTime(21, 00);
            $newEvent->setStartDate($date);
            $newEvent->setEndDate((clone $date)->setTime(23, 00));
            $newEvent->setRegistration(true);

            $eventManager->save($newEvent, true);

            $this->addFlash('success', 'L\'événement a été enregistré');

            return $this->redirectToRoute('calendar_edit', ['event' => $newEvent->getId()]);
        }

        return $this->render('calendar/copy.html.twig', [
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
     * @Route("/mark-all-as-viewed", name="calendar_ack_all")
     */
    public function markAllAsViewed(Request $request, EventService $eventService): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventService->markAllUnreadEventsAsRead($this->getUser());
            $this->addFlash('success', 'Tous les événements ont été marqués comme lu');

            return $this->redirectToRoute('calendar');
        }

        return $this->render('calendar/_mark-all-as-viewed.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{event}", name="calendar_view")
     */
    public function view(Event $event, EventService $eventService): Response
    {
        if ($event->isDeleted()) {
            throw new NotFoundHttpException('event not found');
        }

        $vote = $this->getDoctrine()->getRepository(Vote::class)->findOneBy(['event' => $event, 'user' => $this->getUser()]);
        $choices = [];

        if (null !== $this->getUser()) {
            $eventService->markEventReadByUser($event, $this->getUser());
            foreach ($eventService->findUserChoices($event, $this->getUser()) as $choice) {
                $choices[$choice->getPriority()] = $choice;
            }
        }

        $usersChoices = [];
        foreach ($this->getDoctrine()->getRepository(Choice::class)->findBy(['event' => $event], ['priority' => 'ASC']) as $choice) {
            $usersChoices[$choice->getUser()->getId()][] = $choice;
        }

        return $this->render('calendar/view.html.twig', [
            'event' => $event,
            'userVote' => $vote,
            'modules' => null !== $this->getUser() ? $this->getDoctrine()->getRepository(UserModule::class)->findBy(['user' => $this->getUser()]) : [],
            'choices' => $choices,
            'usersChoices' => $usersChoices,
        ]);
    }

    /**
     * @Route("/{event}/choice/add/{priority}", name="calendar_choice_add")
     * @Route("/{event}/choice/edit/{choice}", name="calendar_choice_edit")
     * @Security("is_granted('CHOICE', event)")
     */
    public function choice(Request $request, ChoiceManager $choiceManager, Event $event, Choice $choice = null, int $priority = null): Response
    {
        if ('calendar_choice_add' === $request->get('_route')) {
            $choice = new Choice();
            $choice->setPriority($priority);
            $choice->setEvent($event);
            $choice->setUser($this->getUser());
            $choice->setCreatedAt(new \DateTime('now'));
        } else {
            if ($choice->getEvent()->getId() != $event->getId()) {
                throw new \InvalidArgumentException('event and choices mismatches');
            }
            if ($choice->getUser()->getId() != $this->getUser()->getId()) {
                throw new UnauthorizedHttpException('private ressource');
            }
        }

        $form = $this->createForm(CalendarChoiceType::class, $choice, ['event' => $event]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (null === $choice->getModule()) {
                $this->getDoctrine()->getManager()->remove($choice);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('warning', 'Le choix a été supprimé');
            } else {
                $choiceManager->save($choice, true);
                $this->addFlash('success', 'Le choix a été enregistré');
            }

            return $this->redirectToRoute('calendar_view', ['event' => $event->getId()]);
        }

        return $this->render($request->isXmlHttpRequest() ? 'calendar/_choice.html.twig' : 'calendar/choice.html.twig',
            [
                'form' => $form->createView(),
                'choice' => $choice,
            ]);
    }
}
