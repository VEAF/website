<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\FilterSelect;
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
            ->select('u');

        $booleanFilterChoices = ['Oui' => true, 'Non' => false];

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

        $table
            ->addColumn(
                (new Column())->setLabel('Statut')
                    ->setSort(['u.status' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('u.status')
                        ->setName('u_status')
                        ->setChoices(array_flip(User::STATUSES))
                        ->setPlaceholder('-')
                        ->disableTranslation() // disable translations of placeholder and values
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        /** @var User $row */
                        $user = $row['object'];
                        return $user->getStatusAsString();
                    })
            );

        $table
            ->addColumn(
                (new Column())->setLabel('DCS')
                    ->setSort(['u.simDcs' => 'asc', 'u.nickname' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('u.simDcs')
                        ->setName('u_simDcs')
                        ->setChoices($booleanFilterChoices)
                        ->setPlaceholder('-')
                        ->disableTranslation() // disable translations of placeholder and values
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        if ($value) {
                            return 'oui';
                        } else {
                            return 'non';
                        }
                    })
            );

        $table
            ->addColumn(
                (new Column())->setLabel('BMS')
                    ->setSort(['u.simBms' => 'asc', 'u.nickname' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('u.simBms')
                        ->setName('u_simBms')
                        ->setChoices($booleanFilterChoices)
                        ->setPlaceholder('-')
                        ->disableTranslation() // disable translations of placeholder and values
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        if ($value) {
                            return 'oui';
                        } else {
                            return 'non';
                        }
                    })
            );

        return $table;
    }

    /**
     * @Route("", name="admin_user_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/user/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_user_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/{user}", name="admin_user_view")
     */
    public function view(User $user): Response
    {
        return $this->render('admin/user/view.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{user}/edit", name="admin_user_edit")
     */
    public function edit(User $user): Response
    {
        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
        ]);
    }

}
