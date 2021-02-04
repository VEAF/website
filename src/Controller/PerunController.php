<?php

namespace App\Controller;

use App\Entity\PerunInstance;
use App\Entity\PerunOnlinePlayer;
use App\Entity\PerunPlayer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
        /** @var PerunInstance[] $instances */
        $instances = $this->getDoctrine()->getRepository(PerunInstance::class)->findBy([],['id'=>'ASC']);

        return $this->render('perun/index.html.twig', [
            'instances' => $instances,
        ]);
    }

    /**
     * @Route("/{instance}", name="perun_instance")
     */
    public function instance(PerunInstance $instance): Response
    {
        /** @var PerunOnlinePlayer[] $onlinePlayers */
        $onlinePlayers = $this->getDoctrine()->getRepository(PerunOnlinePlayer::class)->findBy(['instance' => $instance]);

        // append DTO because no proper link between online player and player tables
        foreach ($onlinePlayers as $onlinePlayer) {
            $onlinePlayer->setPlayer($this->getDoctrine()->getRepository(PerunPlayer::class)->findOneByUcid($onlinePlayer->getUcid()));
        }

        return $this->render('perun/instance.html.twig', [
            'instance' => $instance,
            'onlinePlayers'=> $onlinePlayers,
        ]);
    }
}
