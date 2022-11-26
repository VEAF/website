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
        $clients = Client::fromJson(
            '{
  "Clients": [
    {
      "ClientGuid": "yoWmMefsWE6Fe3uNLQf_lw",
      "Name": "Gibson",
      "Seat": 0,
      "Coalition": 2,
      "AllowRecord": false,
      "RadioInfo": {
        "radios": [
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 121500000.0,
            "modulation": 0,
            "secFreq": 243000000.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 1,
            "freq": 260000000.0,
            "modulation": 0,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 1,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 1,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 1,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 1,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          }
        ],
        "unit": "M-2000C",
        "unitId": 16782082,
        "iff": {
          "control": 0,
          "mode1": 0,
          "mode3": 0,
          "mode4": true,
          "mic": -1,
          "status": 1
        }
      },
      "LatLngPosition": {
        "lat": 41.611848338582,
        "lng": 41.599428735382,
        "alt": 11.646333128696
      }
    },
    {
      "ClientGuid": "0X72FSZyR0OFn-lEoNQCMQ",
      "Name": "Arnokm",
      "Seat": 0,
      "Coalition": 2,
      "AllowRecord": false,
      "RadioInfo": {
        "radios": [
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 0,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 10000.0,
            "modulation": 0,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 1,
            "freq": 251000000.0,
            "modulation": 0,
            "secFreq": 243000000.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 1,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 1,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 1,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          },
          {
            "enc": false,
            "encKey": 0,
            "freq": 1.0,
            "modulation": 3,
            "secFreq": 0.0,
            "retransmit": false
          }
        ],
        "unit": "Mirage-F1CE",
        "unitId": 16782084,
        "iff": {
          "control": 0,
          "mode1": 0,
          "mode3": 0,
          "mode4": false,
          "mic": -1,
          "status": 0
        }
      },
      "LatLngPosition": {
        "lat": 42.184491993486,
        "lng": 42.498248458377,
        "alt": 47.042958276369
      }
    }
  ],
  "ServerVersion": "2.0.7.0"
}');
        dump($clients);

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
