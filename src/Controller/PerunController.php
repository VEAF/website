<?php

namespace App\Controller;

use App\Entity\Server;
use App\Perun\DTO\PayloadSlots;
use App\Perun\Entity\DataRaw;
use App\Perun\Entity\Instance;
use App\Perun\Entity\OnlinePlayer;
use App\Perun\Service\LogStatService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function attendance(Server $server = null, LogStatService $logStatService): Response
    {
        return $this->render('perun/attendance.html.twig', [
            'server' => $server,
            'history24h' => $logStatService->getAttendanceChart('history24h', null === $server ? null : $server->getPerunInstance()),
            'heatmap' => $logStatService->getHeatmapChart('heatmap', null === $server ? null : $server->getPerunInstance()),
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
