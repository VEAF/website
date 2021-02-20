<?php

namespace App\Controller;

use App\Entity\Server;
use App\Perun\Entity\Instance;
use App\Perun\Entity\OnlinePlayer;
use App\Perun\Entity\Player;
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
    public function index(): Response
    {
        /** @var Instance[] $instances */
        $instances = $this->getDoctrine()->getRepository(Instance::class)->findBy([], ['id' => 'ASC']);

        return $this->render('perun/index.html.twig', [
            'instances' => $instances,
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

        return $this->render('perun/instance.html.twig', [
            'server' => $server,
            'onlinePlayers' => $onlinePlayers,
            'history24h' => $logStatService->getAttendanceChart('history24h', $server->getPerunInstance()),
        ]);
    }

    /**
     * @Route("/{server}/attendance", name="perun_instance_attendance")
     * @ParamConverter("server", options={"mapping": {"server": "code"}})
     */
    public function attendance(Server $server, LogStatService $logStatService): Response
    {
        if (null === $server) {
            throw new NotFoundHttpException('server is not linked to a perun instance');
        }

        return $this->render('perun/attendance.html.twig', [
            'server' => $server,
            'history24h' => $logStatService->getAttendanceChart('history24h', $server->getPerunInstance()),
            'heatmap' => $logStatService->getHeatmapChart('heatmap', $server->getPerunInstance()),
        ]);
    }
}
