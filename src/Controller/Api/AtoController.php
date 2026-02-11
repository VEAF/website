<?php

namespace App\Controller\Api;

use App\Entity\Calendar\Event;
use App\Entity\Calendar\Flight;
use App\Entity\Calendar\Slot;
use App\Entity\Module;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/calendar/api/ato")
 */
class AtoController extends AbstractController
{
    /**
     * @Route("/{event}/data", name="api_ato_data", methods={"GET"})
     *
     * @Security("is_granted('EDIT', event)")
     */
    public function data(Event $event): JsonResponse
    {
        $flights = [];
        foreach ($event->getFlights() as $flight) {
            $slots = [];
            foreach ($flight->getSlots() as $slot) {
                $slots[] = [
                    'id' => $slot->getId(),
                    'userId' => $slot->getUser() ? $slot->getUser()->getId() : null,
                    'username' => $slot->getUser() ? $slot->getUser()->getNickname() : $slot->getUsername(),
                    'isRegistered' => null !== $slot->getUser(),
                ];
            }
            $flights[] = [
                'id' => $flight->getId(),
                'name' => $flight->getName(),
                'mission' => $flight->getMission(),
                'aircraftId' => $flight->getAircraft() ? $flight->getAircraft()->getId() : null,
                'aircraftName' => $flight->getAircraft() ? $flight->getAircraft()->getName() : null,
                'nbSlots' => $flight->getNbSlots(),
                'departureBase' => $flight->getDepartureBase(),
                'returnBase' => $flight->getReturnBase(),
                'slots' => $slots,
            ];
        }

        $availablePlayers = [];
        foreach ($event->getVotesByVote(true) as $vote) {
            $availablePlayers[] = [
                'id' => $vote->getUser()->getId(),
                'nickname' => $vote->getUser()->getNickname(),
                'voteType' => 'yes',
            ];
        }
        foreach ($event->getVotesByVote(null) as $vote) {
            $availablePlayers[] = [
                'id' => $vote->getUser()->getId(),
                'nickname' => $vote->getUser()->getNickname(),
                'voteType' => 'maybe',
            ];
        }

        $aircraft = $this->getDoctrine()->getRepository(Module::class)
            ->createQueryBuilder('m')
            ->andWhere('m.type IN (:types)')
            ->setParameter('types', [Module::TYPE_AIRCRAFT, Module::TYPE_HELICOPTER, Module::TYPE_SPECIAL])
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();

        $aircraftList = [];
        foreach ($aircraft as $module) {
            $aircraftList[] = [
                'id' => $module->getId(),
                'name' => $module->getName(),
            ];
        }

        return $this->json([
            'eventId' => $event->getId(),
            'flights' => $flights,
            'availablePlayers' => $availablePlayers,
            'aircraft' => $aircraftList,
            'missions' => Flight::MISSIONS,
        ]);
    }

    /**
     * @Route("/{event}/save", name="api_ato_save", methods={"POST"})
     *
     * @Security("is_granted('EDIT', event)")
     */
    public function save(Request $request, Event $event): JsonResponse
    {
        $token = $request->headers->get('X-CSRF-Token');
        if (!$this->isCsrfTokenValid('ato_save', $token)) {
            return $this->json(['error' => 'Token CSRF invalide'], 403);
        }

        $data = json_decode($request->getContent(), true);
        if (null === $data || !isset($data['flights'])) {
            return $this->json(['error' => 'Données JSON invalides'], 400);
        }

        $em = $this->getDoctrine()->getManager();
        $userRepository = $this->getDoctrine()->getRepository(User::class);
        $moduleRepository = $this->getDoctrine()->getRepository(Module::class);

        $existingFlights = [];
        foreach ($event->getFlights() as $flight) {
            $existingFlights[$flight->getId()] = $flight;
        }

        $processedFlightIds = [];

        foreach ($data['flights'] as $flightData) {
            $flightId = $flightData['id'] ?? null;

            if ($flightId && isset($existingFlights[$flightId])) {
                $flight = $existingFlights[$flightId];
                $processedFlightIds[] = $flightId;
            } else {
                $flight = new Flight();
                $event->addFlight($flight);
            }

            $flight->setName(!empty($flightData['name']) ? $flightData['name'] : 'sans nom');
            $flight->setMission($flightData['mission'] ?? null);
            $flight->setNbSlots(!empty($flightData['nbSlots']) ? (int) $flightData['nbSlots'] : 4);
            $flight->setDepartureBase(!empty($flightData['departureBase']) ? $flightData['departureBase'] : null);
            $flight->setReturnBase(!empty($flightData['returnBase']) ? $flightData['returnBase'] : null);

            $aircraftId = $flightData['aircraftId'] ?? null;
            if ($aircraftId) {
                $module = $moduleRepository->find($aircraftId);
                if ($module) {
                    $flight->setAircraft($module);
                }
            }

            // Remove existing slots and rebuild
            foreach ($flight->getSlots() as $existingSlot) {
                $flight->removeSlot($existingSlot);
            }

            foreach (($flightData['slots'] ?? []) as $slotData) {
                $slot = new Slot();
                if (!empty($slotData['userId'])) {
                    $user = $userRepository->find($slotData['userId']);
                    if ($user) {
                        $slot->setUser($user);
                    } else {
                        continue;
                    }
                } elseif (!empty($slotData['username'])) {
                    $slot->setUsername($slotData['username']);
                } else {
                    continue;
                }
                $flight->addSlot($slot);
            }

            $em->persist($flight);
        }

        // Remove flights that are no longer in the payload
        foreach ($existingFlights as $id => $flight) {
            if (!in_array($id, $processedFlightIds)) {
                $event->removeFlight($flight);
            }
        }

        $event->setAto(count($event->getFlights()) > 0);
        $em->flush();

        return $this->json(['success' => true, 'flightCount' => count($event->getFlights())]);
    }
}
