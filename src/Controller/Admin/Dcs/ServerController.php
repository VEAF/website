<?php

namespace App\Controller\Admin\Dcs;

use App\Entity\Server;
use App\Form\ServerType;
use Doctrine\ORM\EntityManagerInterface;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/dcs/server")
 */
class ServerController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Server::class)->createQueryBuilder('s')
            ->select('s,i')
            ->leftJoin('s.perunInstance', 'i');

        $table = (new Table())
            ->setId('admin_dcs_server_list')
            ->setPath($this->generateUrl('admin_dcs_server_list_ajax'))
            ->setTemplate('admin/dcs/server/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 's');

        $table
            ->addColumn(
                (new Column())->setLabel('Serveur')
                    ->setSort(['s.name' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('s.name')
                        ->setName('s_name')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Code')
                    ->setSort(['s.code' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('s.code')
                        ->setName('s_code')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Instance Perun')
                    ->setSort(['i.id' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('i.id')
                        ->setName('i_id')
                    )
            );

        return $table;
    }

    /**
     * @Route("", name="admin_dcs_server_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/dcs/server/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_dcs_server_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/add", name="admin_dcs_server_add")
     * @Route("/{server}/edit", name="admin_dcs_server_edit")
     */
    public function edit(EntityManagerInterface $entityManager, Request $request, Server $server = null): Response
    {
        if (null === $server) {
            $server = new Server();
        }

        $form = $this->createForm(ServerType::class, $server);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($server);
            $entityManager->flush();
            $this->addFlash('success', 'Le serveur a été enregistré');

            return $this->redirectToRoute('admin_dcs_server_view', ['server' => $server->getId()]);
        }

        return $this->render('admin/dcs/server/edit.html.twig', [
            'form' => $form->createView(),
            'server' => $server,
        ]);
    }

    /**
     * @Route("/{server}", name="admin_dcs_server_view")
     */
    public function view(Server $server): Response
    {
        return $this->render('admin/dcs/server/view.html.twig', [
            'server' => $server,
        ]);
    }
}
