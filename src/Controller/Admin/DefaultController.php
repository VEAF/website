<?php

namespace App\Controller\Admin;

use App\Entity\File;
use App\Entity\Module;
use App\Entity\Page;
use App\Entity\Player;
use App\Entity\User;
use App\Perun\Entity\Player as PerunPlayer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("", name="admin_index")
     */
    public function index(): Response
    {
        $data = [];

        $data['total'] = [
            'users' => $this->getDoctrine()->getRepository(User::class)->count([]),
            'players' => $this->getDoctrine()->getRepository(Player::class)->count([]),
            'perunPlayers' => $this->getDoctrine()->getRepository(PerunPlayer::class)->count([]),
            'modules' => $this->getDoctrine()->getRepository(Module::class)->count([]),
            'pages' => $this->getDoctrine()->getRepository(Page::class)->count([]),
            'files' => $this->getDoctrine()->getRepository(File::class)->count([]),
        ];

        return $this->render('admin/default/index.html.twig', $data);
    }
}
