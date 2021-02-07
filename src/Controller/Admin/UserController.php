<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(User::class)->createQueryBuilder('u')
            ->select("u");

        $table = (new Table())
            ->setId('admin_user_list')
            ->setPath($this->generateUrl('admin_user_list_ajax'))
            ->setTemplate('admin/user/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'u');

        $table
            ->addColumn(
                (new Column())->setLabel('Email')
                    ->setSort(['u.email' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('u.email')
                        ->setName('u_email')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Pseudo')
                    ->setSort(['u.nickname' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('u.nickname')
                        ->setName('u_nickname')
                    )
            );

        return $table;
    }

    /**
     * @Route("", name="admin_user_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/user/list.html.twig', [
            'table'=>$tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_user_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }
}
