<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\User;
use App\Perun\Repository\OnlinePlayerRepository;
use App\Repository\Calendar\EventRepository;
use App\Repository\Menu\ItemRepository;
use App\Service\TeamSpeak3ClientCache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $data = [];

        $data['maps'] = $this->getDoctrine()->getRepository(Module::class)->findBy(['type' => Module::TYPE_MAP, 'landingPage' => true], ['landingPageNumber' => 'asc', 'name' => 'asc']);
        $data['aircrafts'] = $this->getDoctrine()->getRepository(Module::class)->findBy(['type' => Module::TYPE_AIRCRAFT, 'landingPage' => true], ['landingPageNumber' => 'asc', 'name' => 'asc']);
        $data['helicopters'] = $this->getDoctrine()->getRepository(Module::class)->findBy(['type' => Module::TYPE_HELICOPTER, 'landingPage' => true], ['landingPageNumber' => 'asc', 'name' => 'asc']);
        $data['specials'] = $this->getDoctrine()->getRepository(Module::class)->findBy(['type' => Module::TYPE_SPECIAL, 'landingPage' => true], ['landingPageNumber' => 'asc', 'name' => 'asc']);

        return $this->render('default/index.html.twig', $data);
    }

    /**
     * @Route("/fr/association/le-bureau", name="office")
     */
    public function office(): Response
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        $data = [
            'president' => $userRepo->findOneByStatus(User::STATUS_PRESIDENT),
            'presidentDeputy' => $userRepo->findOneByStatus(User::STATUS_PRESIDENT_DEPUTY),
            'treasurer' => $userRepo->findOneByStatus(User::STATUS_TREASURER),
            'treasurerDeputy' => $userRepo->findOneByStatus(User::STATUS_TREASURER_DEPUTY),
            'secretary' => $userRepo->findOneByStatus(User::STATUS_SECRETARY),
            'secretaryDeputy' => $userRepo->findOneByStatus(User::STATUS_SECRETARY_DEPUTY),
        ];

        return $this->render('default/office.html.twig', $data);
    }

    /**
     * Top menu.
     */
    public function _header(OnlinePlayerRepository $onlinePlayerRepository, EventRepository $eventRepository, ItemRepository $itemRepository, TeamSpeak3ClientCache $tsService): Response
    {
        $nextDays = 7;
        $data = [];

        $data['menuItems'] = $itemRepository->findBy(['menu' => null, 'enabled' => true], ['position' => 'ASC']);

        $data['connectedUsers'] = $onlinePlayerRepository->countRealPlayersByInstance();
        $data['newEvents'] = (null === $this->getUser() ? 0 : $eventRepository->countNewEventsByUser($this->getUser()));
        $data['nextEvents'] = $eventRepository->countNextEvents($nextDays);
        $data['nextDays'] = $nextDays;
        $data['teamSpeakClientsCount'] = $tsService->countClients();

        $response = $this->render('default/_header.html.twig', $data);

        return $response;
    }
}
