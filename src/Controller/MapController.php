<?php

namespace App\Controller;

use App\Entity\Server;
use App\Perun\DTO\Client;
use App\Perun\Entity\DataRaw;
use App\Service\MapService;
use App\Service\Perun\InstanceService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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

        // load clients positions
        $dataClients = $this->getDoctrine()->getRepository(DataRaw::class)->findOneBy(['instance' => $server->getPerunInstance(), 'type' => DataRaw::TYPE_CLIENTS]);
        $clients = Client::fromJson($dataClients->getPayload());

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        return $this->render('map/index.html.twig', [
            'server' => $server,
            'missionData' => $missionData,
            'mapData' => $mapService->getMapData($missionData),
            'clients' => json_decode($serializer->serialize($clients, 'json'), true),
        ]);
    }
}
