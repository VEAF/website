<?php

namespace App\Controller\Admin\Dcs;

use App\Form\PerunPlayerLinkType;
use App\Entity\Player;
use App\Form\PlayerLinkType;
use App\Repository\PlayerRepository;
use App\Perun\Repository\PlayerRepository as PerunPlayerRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tetranz\Select2EntityBundle\Service\AutocompleteService;

/**
 * @Route("/admin/dcs/player")
 */
class PlayerController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Player::class)->createQueryBuilder('p')
            ->select('p, u')
            ->leftJoin('p.user', 'u');

        $table = (new Table())
            ->setId('admin_dcs_player_list')
            ->setPath($this->generateUrl('admin_dcs_player_list_ajax'))
            ->setTemplate('admin/dcs/player/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'p');

        $table
            ->addColumn(
                (new Column())->setLabel('Pseudo')
                    ->setSort(['p.nickname' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('p.nickname')
                        ->setName('p_nickname')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Utilisateur')
                    ->setSort(['u.nickname' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('u.nickname')
                        ->setName('u_nickname')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Ucid')
                    ->setSort(['p.ucid' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('p.ucid')
                        ->setName('p_ucid')
                    )
            );

        return $table;
    }

    /**
     * @Route("", name="admin_dcs_player_list")
     */
    public function list(PlayerRepository $playerRepository, TableService $tableService): Response
    {
        $data = [];
        $data['table'] = $tableService->createFormView($this->getTable());
        $data['autolink'] = $playerRepository->countAutolink();

        return $this->render('admin/dcs/player/list.html.twig', $data);
    }

    /**
     * @Route("/_ajax", name="admin_dcs_player_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * Used on player link form.
     *
     * @Route("/autocomplete", name="admin_dcs_player_autocomplete")
     */
    public function search(AutocompleteService $autocompleteService, Request $request): Response
    {
        $result = $autocompleteService->getAutocompleteResults($request, PlayerLinkType::class);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{player}/edit", name="admin_dcs_player_edit")
     */
    public function edit(Request $request, Player $player): Response
    {
        $form = $this->createForm(PlayerLinkType::class, $player);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $player->getUser();
            $user->setPlayer($player); // User own the OneToOne relation
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le joueur a été enregistré');

            return $this->redirectToRoute('admin_dcs_player_view', ['player' => $player->getId()]);
        }

        return $this->render('admin/dcs/player/edit.html.twig', [
            'form' => $form->createView(),
            'player' => $player,
        ]);
    }

    /**
     * @Route("/{player}/remove-link", name="admin_dcs_player_remove_link")
     */
    public function removeLink(Request $request, Player $player): Response
    {
        $form = $this->createFormBuilder()->setMethod('POST')->getForm();

        $form->handleRequest($request);

        if (null === $player->getUser()) {
            return $this->redirectToRoute('admin_dcs_player_view', ['player' => $player->getId()]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $player->getUser();
            $user->setPlayer(null); // User own the OneToOne relation
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le joueur a été enregistré');

            return $this->redirectToRoute('admin_dcs_player_view', ['player' => $player->getId()]);
        }

        return $this->render('admin/dcs/player/remove_link.html.twig', [
            'form' => $form->createView(),
            'player' => $player,
        ]);
    }

    /**
     * @Route("/autolink", name="admin_dcs_player_autolink")
     */
    public function admin_dcs_player_autolink(EntityManagerInterface $entityManager, PlayerRepository $playerRepository, PerunPlayerRepository $perunPlayerRepository): Response
    {
        $associated = 0;

        foreach ($playerRepository->findAll() as $player) {
            if (null === $player->getUser() && null !== $player->getUcid()) {
                $perunPlayer = $perunPlayerRepository->findOneByUcid($player->getUcid());
                if (null !== $perunPlayer && null !== $perunPlayer->getUser()) {
                    // user own the one to one link
                    $perunPlayer->getUser()->setPlayer($player);
                    $associated++;
                }
            }
        }

        $entityManager->flush();
        $this->addFlash('success', sprintf('%d utilisateurs associés', $associated));

        return $this->redirectToRoute('admin_dcs_player_list');
    }

    /**
     * @Route("/{player}", name="admin_dcs_player_view")
     */
    public function view(Request $request, Player $player): Response
    {
        return $this->render('admin/dcs/player/view.html.twig', [
            'player' => $player,
        ]);
    }
}
