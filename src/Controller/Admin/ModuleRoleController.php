<?php

namespace App\Controller\Admin;

use App\Entity\ModuleRole;
use App\Form\ModuleRoleType;
use Doctrine\ORM\EntityManagerInterface;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/module-role")
 */
class ModuleRoleController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(ModuleRole::class)->createQueryBuilder('m')
            ->select('m');

        $table = (new Table())
            ->setId('admin_module_role_list')
            ->setPath($this->generateUrl('admin_module_role_list_ajax'))
            ->setTemplate('admin/module-role/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'm');

        $table
            ->addColumn(
                (new Column())->setLabel('Code')
                    ->setSort(['m.code' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('m.code')
                        ->setName('m_code')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Nom')
                    ->setSort(['m.name' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('m.name')
                        ->setName('m_name')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Position')
                    ->setSort(['m.position' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('m.position')
                        ->setName('m_position')
                    )
            );

        return $table;
    }

    /**
     * @Route("", name="admin_module_role_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/module-role/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_module_role_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/add", name="admin_module_role_add")
     */
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $role = new ModuleRole();
        $form = $this->createForm(ModuleRoleType::class, $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($role);
            $entityManager->flush();
            $this->addFlash('success', 'Le système de module a été enregistrée');

            return $this->redirectToRoute('admin_module_role_view', ['role' => $role->getId()]);
        }

        return $this->render('admin/module-role/edit.html.twig', [
            'form' => $form->createView(),
            'role' => $role,
        ]);
    }

    /**
     * @Route("/{role}/delete", name="admin_module_role_delete")
     */
    public function delete(EntityManagerInterface $entityManager, Request $request, ModuleRole $role): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($role);
            $entityManager->flush();

            $this->addFlash('success', 'Le système a été supprimé');

            return $this->redirectToRoute('admin_module_role_list');
        }

        return $this->render('admin/module-role/delete.html.twig', [
            'form' => $form->createView(),
            'role' => $role,
        ]);
    }

    /**
     * @Route("/{role}", name="admin_module_role_view")
     */
    public function view(ModuleRole $role): Response
    {
        return $this->render('admin/module-role/view.html.twig', [
            'role' => $role,
        ]);
    }

    /**
     * @Route("/{role}/edit", name="admin_module_role_edit")
     */
    public function edit(EntityManagerInterface $entityManager, Request $request, ModuleRole $role): Response
    {
        $form = $this->createForm(ModuleRoleType::class, $role);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le rôle a été enregistré');

            return $this->redirectToRoute('admin_module_role_view', ['role' => $role->getId()]);
        }

        return $this->render('admin/module-role/edit.html.twig', [
            'form' => $form->createView(),
            'role' => $role,
        ]);
    }
}
