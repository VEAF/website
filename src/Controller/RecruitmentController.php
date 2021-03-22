<?php

namespace App\Controller;

use App\Entity\Recruitment\Event;
use App\Entity\User;
use App\Form\RecruitmentEventType;
use App\Manager\Recruitment\EventManager;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\Workflow\WorkflowInterface;

/**
 * @Route("/recruitment")
 * @Security("is_granted('ROLE_USER')")
 */
class RecruitmentController extends AbstractController
{
    private Workflow $recruitmentWorkflow;

    public function __construct(WorkflowInterface $recruitmentWorkflow)
    {
        $this->recruitmentWorkflow = $recruitmentWorkflow;
    }

    /**
     * @Route("/_change-for-cadet", name="recruitment_change_for_cadet")
     */
    public function changeForCadet(Request $request): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest() && !$form->isSubmitted()) {
            throw new UnauthorizedHttpException('change-for-cadet bad call');
        }

        if (!$this->recruitmentWorkflow->can($this->getUser(), 'to_apply')) {
            throw new UnauthorizedHttpException('transition forbidden');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recruitmentWorkflow->apply($this->getUser(), 'to_apply');
            $this->addFlash('success', 'J\'ai rejoint le programme Cadet');
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('recruitment/_change-for-cadet.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/_change-for-guest", name="recruitment_change_for_guest")
     */
    public function changeForGuest(Request $request): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest() && !$form->isSubmitted()) {
            throw new UnauthorizedHttpException('change-for-guest bad call');
        }

        if (!$this->recruitmentWorkflow->can($this->getUser(), 'guest')) {
            throw new UnauthorizedHttpException('transition forbidden');
        }

        if ($form->isSubmitted() && $form->isValid()) {

            $this->recruitmentWorkflow->apply($this->getUser(), 'guest');
            $this->addFlash('success', 'J\'ai rejoint le groupe des invités');

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('profile');
        }

        return $this->render('recruitment/_change-for-guest.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{user}/_mark-presentation", name="recruitment_mark_presentation")
     * @Security("is_granted('PRESENTATION', user)")
     */
    public function markPresentation(Request $request, User $user, UserService $userService): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if (!$request->isXmlHttpRequest() && !$form->isSubmitted()) {
            throw new UnauthorizedHttpException('mark-presentation bad call');
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $userService->markPresentation($user, $this->getUser());
            $this->addFlash('success', 'La présentation est marquée comme effectuée');
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_view', ['user' => $user->getNickname()]);
        }

        return $this->render('recruitment/_mark-presentation.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]);
    }

    /**
     * @Route("/{user}/add-activity", name="recruitment_add_activity")
     * @Security("is_granted('ADD_ACTIVITY', user)")
     */
    public function addActivity(Request $request, User $user, EventManager $eventManager): Response
    {
        $event = new Event();
        $event->setType(Event::TYPE_ACTIVITY);
        $event->setUser($user);
        $event->setValidator($this->getUser());
        $form = $this->createForm(RecruitmentEventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setCadetFlights($user->getCadetFlights() + 1);
            $eventManager->save($event);

            $this->addFlash('success', 'Le vol a été enregistré');
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_view', ['user' => $user->getNickname()]);
        }

        return $this->render($request->isXmlHttpRequest() ? 'recruitment/_add-activity.html.twig' : 'recruitment/add-activity.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]);
    }

    /**
     * @Route("/{user}/activities", name="recruitment_activities")
     * @Security("is_granted('ADD_ACTIVITY', user)")
     */
    public function _activities(User $user): Response
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findBy(['user' => $user], ['createdAt' => 'ASC']);

        return $this->render('recruitment/_activities.html.twig',
            [
                'events' => $events,
                'user' => $user,
            ]);
    }

}
