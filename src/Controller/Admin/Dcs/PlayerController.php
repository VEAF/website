<?php

namespace App\Controller\Admin\Dcs;

use App\Entity\User;
use App\Perun\Entity\Player;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("", name="admin_dcs_player_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/dcs/player/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_dcs_player_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }
}
