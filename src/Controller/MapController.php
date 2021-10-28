<?php

namespace App\Controller;

use App\Entity\Server;
use App\Service\MapService;
use App\Service\Perun\InstanceService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/map")
 */
class MapController extends AbstractController
{
    /**
     * @Route("/{server}", name="map_index")
     * @ParamConverter("server", options={"mapping": {"server": "code"}})
     */
    public function index(Server $server, InstanceService $instanceService, MapService $mapService): Response
    {
        $missionData = $instanceService->getMission($server->getPerunInstance(), true, 'prod' == $this->getParameter('kernel.environment'));

        return $this->render('map/index.html.twig', [
            'server' => $server,
            'missionData' => $missionData,
            'mapData' => $mapService->getMapData($missionData),
        ]);
    }
}
