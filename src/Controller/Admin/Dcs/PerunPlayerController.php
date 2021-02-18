<?php

namespace App\Controller\Admin\Dcs;

use App\Form\PerunPlayerLinkType;
use App\Perun\Entity\Player;
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
 * @Route("/admin/dcs/perun-player")
 */
class PerunPlayerController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Player::class)->createQueryBuilder('p')
            ->select('p, u')
            ->leftJoin('p.user', 'u');

        $table = (new Table())
            ->setId('admin_dcs_player_list')
            ->setPath($this->generateUrl('admin_dcs_perun_player_list_ajax'))
            ->setTemplate('admin/dcs/perun-player/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'p');

        $table
            ->addColumn(
                (new Column())->setLabel('Dernier pseudo')
                    ->setSort(['p.lastName' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('p.lastName')
                        ->setName('p_lastName')
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
     * @Route("", name="admin_dcs_perun_player_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/dcs/perun-player/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_dcs_perun_player_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * Used on player link form.
     *
     * @Route("/autocomplete", name="admin_dcs_perun_player_autocomplete")
     */
    public function search(AutocompleteService $autocompleteService, Request $request): Response
    {
        $result = $autocompleteService->getAutocompleteResults($request, PerunPlayerLinkType::class);

        return new JsonResponse($result);
    }

    /**
     * @Route("/{player}/edit", name="admin_dcs_perun_player_edit")
     */
    public function edit(Request $request, Player $player): Response
    {
        $form = $this->createForm(PerunPlayerLinkType::class, $player);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $player->getUser();
            $user->setPerunPlayer($player); // User own the OneToOne relation
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le joueur a été enregistré');

            return $this->redirectToRoute('admin_dcs_perun_player_view', ['player' => $player->getId()]);
        }

        return $this->render('admin/dcs/perun-player/edit.html.twig', [
            'form' => $form->createView(),
            'player' => $player,
        ]);
    }

    /**
     * @Route("/{player}/remove-link", name="admin_dcs_perun_player_remove_link")
     */
    public function removeLink(Request $request, Player $player): Response
    {
        $form = $this->createFormBuilder()->setMethod('POST')->getForm();

        $form->handleRequest($request);

        if (null === $player->getUser()) {
            return $this->redirectToRoute('admin_dcs_perun_player_view', ['player' => $player->getId()]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $player->getUser();
            $user->setPerunPlayer(null); // User own the OneToOne relation
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Le joueur a été enregistré');

            return $this->redirectToRoute('admin_dcs_perun_player_view', ['player' => $player->getId()]);
        }

        return $this->render('admin/dcs/perun-player/remove_link.html.twig', [
            'form' => $form->createView(),
            'player' => $player,
        ]);
    }

    /**
     * @Route("/{player}", name="admin_dcs_perun_player_view")
     */
    public function view(Request $request, Player $player): Response
    {
        return $this->render('admin/dcs/perun-player/view.html.twig', [
            'player' => $player,
        ]);
    }
}
