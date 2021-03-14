<?php

namespace App\Controller;

use App\Entity\Server;
use App\Perun\DTO\PayloadSlots;
use App\Perun\Entity\DataRaw;
use App\Perun\Entity\Instance;
use App\Perun\Entity\OnlinePlayer;
use App\Perun\Entity\Player;
use App\Perun\Service\LogStatService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stats/dcs")
 */
class PerunController extends AbstractController
{
    /**
     * @Route("", name="perun_index")
     */
    public function index(LogStatService $logStatService): Response
    {
        /** @var Instance[] $instances */
        $instances = $this->getDoctrine()->getRepository(Instance::class)->findBy([], ['id' => 'ASC']);

        return $this->render('perun/index.html.twig', [
            'instances' => $instances,
            'history24h' => $logStatService->getAttendanceChart('history24h'),
        ]);
    }

    /**
     * @Route("/attendance", name="perun_attendance")
     * @Route("/{server}/attendance", name="perun_instance_attendance", options={})
     * @ParamConverter("server", options={"mapping": {"server": "code"}})
     */
    public function attendance(Request $request, Server $server = null, LogStatService $logStatService): Response
    {
        // end of period
        try {
            $timeTo = new \DateTime((new \DateTime($request->get('timeTo', 'now')))->format('Y-m-d 23:59:59'));
            if ($request->get('timeFrom')) {
                $timeFrom = new \DateTime((new \DateTime($request->get('timeFrom')))->format('Y-m-d 00:00:00'));
            } else {
                $timeFrom = new \DateTime((clone($timeTo))->format('Y-m-d 00:00:00'));
            }
        } catch (\Exception $e) {
            return $this->redirectToRoute('perun_attendance');
        }

        return $this->render('perun/attendance.html.twig', [
            'server' => $server,
            'history24h' => $logStatService->getAttendanceChart('history24h', null === $server ? null : $server->getPerunInstance(), $timeFrom, $timeTo),
            'heatmap' => $logStatService->getHeatmapChart('heatmap', null === $server ? null : $server->getPerunInstance(), 4, $request->get('filter', 'all')),
            'filter' => $request->get('filter', 'all'),
            'route' => $request->get('_route'),
            'routeParams' => ['server' => null === $server ? null : $server->getCode()],
            'timeTo' => $timeTo,
            'timeFrom' => $timeFrom,
            'yesterday' => [
                'from' => new \DateTime((new \DateTime('now'))->modify('-24 hours')->format('Y-m-d 0:0:0')),
                'to' => new \DateTime((new \DateTime('now'))->modify('-24 hours')->format('Y-m-d 23:59:59')),
            ],
            'previous' => [
                'from' => (clone $timeFrom)->modify('-1 day'),
                'to' => (clone $timeTo)->modify('-1 day'),
            ],
            'next' => [
                'from' => (clone $timeFrom)->modify('+1 day'),
                'to' => (clone $timeTo)->modify('+1 day'),
            ],
        ]);
    }

    /**
     * @Route("/{server}", name="perun_instance")
     * @ParamConverter("server", options={"mapping": {"server": "code"}})
     */
    public function instance(Server $server, LogStatService $logStatService): Response
    {
        if (null === $server) {
            throw new NotFoundHttpException('server is not linked to a perun instance');
        }

        /** @var OnlinePlayer[] $onlinePlayers */
        $onlinePlayers = $this->getDoctrine()->getRepository(OnlinePlayer::class)->findRealPlayersByInstance($server->getPerunInstance());

        // append DTO because no proper link between online player and player tables
        foreach ($onlinePlayers as $onlinePlayer) {
            $onlinePlayer->setPlayer($this->getDoctrine()->getRepository(Player::class)->findOneByUcid($onlinePlayer->getUcid()));
        }

        // load map slots information
        $missionSlots = null;
        $dataSlots = $this->getDoctrine()->getRepository(DataRaw::class)->findOneBy(['instance' => $server->getPerunInstance(), 'type' => DataRaw::TYPE_SLOTS]);
        if (null !== $dataSlots) {
            $missionSlots = PayloadSlots::createFromJsonArray(\json_decode($dataSlots->getPayload(), true));
        }

        return $this->render('perun/instance.html.twig', [
            'server' => $server,
            'onlinePlayers' => $onlinePlayers,
            'missionSlots' => $missionSlots,
            'history24h' => $logStatService->getAttendanceChart('history24h', $server->getPerunInstance()),
        ]);
    }
}
