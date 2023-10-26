<?php

namespace App\Controller;

use App\Perun\Entity\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class MetricsController extends AbstractController
{
    /**
     * @Route("/metrics", name="metrics")
     */
    public function metrics(): Response
    {
        return new StreamedResponse(
            function (): void {
                echo sprintf('dcs_exporter{version="%s"} 1' . PHP_EOL, $this->getParameter('app_version'));

                /** @var Instance[] $instances */
                $instances = $this->getDoctrine()->getRepository(Instance::class)->findBy([], ['id' => 'ASC']);

                foreach ($instances as $instance) {
                    // numeric metrics
                    $metrics = [
                        'dcs_up' => $instance->isAlive() + 0,
                        'dcs_mission_time' => round($instance->getModelTime()),
                        'dcs_players_count' => $instance->getPlayersCount(),
                        'dcs_paused' => $instance->isPaused(),
                        'dcs_online_time' => round($instance->getRealTime()),
                    ];
                    foreach ($metrics as $metric => $metricValue) {
                        echo sprintf('%s{server="%s"} %d' . PHP_EOL, $metric, $instance->getServer()->getCode(), $metricValue);
                    }
                    // theatre, strings are not supported by prometheus
                    echo sprintf('%s{server="%s",theatre="%s"} 1' . PHP_EOL, $metric, $instance->getServer()->getCode(), $instance->getTheater());
                }
            },
            Response::HTTP_OK,
            ['Content-Type' => 'text/plain']
        );
    }
}
