<?php

namespace App\Controller;

use DcsServerBot\Api\InfoApi;
use DcsServerBot\ApiException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stats/dcs")
 */
class DcsBotController extends AbstractController
{
    /**
     * @Route("", name="dcsbot_stats")
     */
    public function stats(InfoApi $infoApi): Response
    {
        $servers = [];
        $serverStats = null;
        $error = null;

        try {
            $servers = $infoApi->serversServerapiServersGet();
            $serverStats = $infoApi->serverstatsServerapiServerstatsGet();
        } catch (ApiException $e) {
            $error = 'Impossible de contacter le service DCSServerBot: '.$e->getMessage();
        }

        return $this->render('dcsbot/stats.html.twig', [
            'servers' => $servers,
            'serverStats' => $serverStats,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/{serverName}", name="dcsbot_server")
     */
    public function server(string $serverName, InfoApi $infoApi): Response
    {
        $server = null;
        $serverStats = null;
        $error = null;

        try {
            $servers = $infoApi->serversServerapiServersGet($serverName);
            if (empty($servers)) {
                throw $this->createNotFoundException('Serveur non trouvé');
            }
            $server = $servers[0];
            $serverStats = $infoApi->serverstatsServerapiServerstatsGet($serverName);
        } catch (ApiException $e) {
            $error = 'Impossible de contacter le service DCSServerBot: '.$e->getMessage();
        }

        return $this->render('dcsbot/server.html.twig', [
            'server' => $server,
            'serverStats' => $serverStats,
            'error' => $error,
        ]);
    }
}
