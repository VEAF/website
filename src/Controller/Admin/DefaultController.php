<?php

namespace App\Controller\Admin;

use App\Entity\Calendar\Event;
use App\Entity\File;
use App\Entity\Menu\Item;
use App\Entity\Module;
use App\Entity\Page;
use App\Entity\Player;
use App\Entity\Url;
use App\Entity\User;
use App\Entity\Variant;
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
            'variants' => $this->getDoctrine()->getRepository(Variant::class)->count([]),
            'variantsWithoutModule' => $this->getDoctrine()->getRepository(Variant::class)->count(['module' => null]),
            'pages' => $this->getDoctrine()->getRepository(Page::class)->count([]),
            'files' => $this->getDoctrine()->getRepository(File::class)->count([]),
            'calendarEvents' => $this->getDoctrine()->getRepository(Event::class)->count([]),
            'urls' => $this->getDoctrine()->getRepository(Url::class)->count([]),
            'menuItems' => $this->getDoctrine()->getRepository(Item::class)->count([]),
            'cadetsWaitingForPresentation' => $this->getDoctrine()->getRepository(User::class)->count(['status' => User::STATUS_CADET, 'needPresentation' => true]),
        ];

        return $this->render('admin/default/index.html.twig', $data);
    }
}
