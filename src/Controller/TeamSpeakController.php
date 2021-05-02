<?php

namespace App\Controller;

use App\Service\TeamSpeak3Client;
use App\Service\TeamSpeak3ClientCache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use TeamSpeak3;
use TeamSpeak3_Exception;

/**
 * @Route("/teamspeak")
 */
class TeamSpeakController extends AbstractController
{
    /**
     * Test
     *
     * @Route("", name="teamspeak_index")
     */
    public function index(TeamSpeak3ClientCache $service): Response
    {
        $data = [];
        $data['clients'] = $service->getClients();
        $data['channels'] = $service->getChannels();

        return $this->render('teamspeak/index.html.twig', $data);
    }
}
