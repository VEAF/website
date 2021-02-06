<?php

namespace App\Controller;

use App\Perun\Entity\Instance;
use App\Perun\Entity\OnlinePlayer;
use App\Perun\Entity\PerunPlayer;
use App\Perun\Entity\Player;
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
        /** @var Instance[] $instances */
        $instances = $this->getDoctrine()->getRepository(Instance::class)->findBy([],['id'=>'ASC']);

        return $this->render('perun/index.html.twig', [
            'instances' => $instances,
        ]);
    }

    /**
     * @Route("/{instance}", name="perun_instance")
     */
    public function instance(Instance $instance): Response
    {
        /** @var OnlinePlayer[] $onlinePlayers */
        $onlinePlayers = $this->getDoctrine()->getRepository(OnlinePlayer::class)->findRealPlayersByInstance($instance);

        // append DTO because no proper link between online player and player tables
        foreach ($onlinePlayers as $onlinePlayer) {
            $onlinePlayer->setPlayer($this->getDoctrine()->getRepository(Player::class)->findOneByUcid($onlinePlayer->getUcid()));
        }

        return $this->render('perun/instance.html.twig', [
            'instance' => $instance,
            'onlinePlayers'=> $onlinePlayers,
        ]);
    }
}
