<?php

namespace App\Controller\Admin;

use App\Entity\ModuleSystem;
use App\Form\ModuleSystemType;
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
 * @Route("/admin/module-system")
 */
class ModuleSystemController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(ModuleSystem::class)->createQueryBuilder('m')
            ->select('m');

        $table = (new Table())
            ->setId('admin_module_system_list')
            ->setPath($this->generateUrl('admin_module_system_list_ajax'))
            ->setTemplate('admin/module-system/_list.html.twig')
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
     * @Route("", name="admin_module_system_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/module-system/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_module_system_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/add", name="admin_module_system_add")
     */
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $system = new ModuleSystem();
        $form = $this->createForm(ModuleSystemType::class, $system);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($system);
            $entityManager->flush();
            $this->addFlash('success', 'Le système de module a été enregistrée');

            return $this->redirectToRoute('admin_module_system_view', ['system' => $system->getId()]);
        }

        return $this->render('admin/module-system/edit.html.twig', [
            'form' => $form->createView(),
            'system' => $system,
        ]);
    }

    /**
     * @Route("/{system}/delete", name="admin_module_system_delete")
     */
    public function delete(EntityManagerInterface $entityManager, Request $request, ModuleSystem $system): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->remove($system);
            $entityManager->flush();

            $this->addFlash('success', 'Le système a été supprimé');

            return $this->redirectToRoute('admin_module_system_list');
        }

        return $this->render('admin/module-system/delete.html.twig', [
            'form' => $form->createView(),
            'system' => $system,
        ]);
    }

    /**
     * @Route("/{system}", name="admin_module_system_view")
     */
    public function view(ModuleSystem $system): Response
    {
        return $this->render('admin/module-system/view.html.twig', [
            'system' => $system,
        ]);
    }

    /**
     * @Route("/{system}/edit", name="admin_module_system_edit")
     */
    public function edit(EntityManagerInterface $entityManager, Request $request, ModuleSystem $system): Response
    {
        $form = $this->createForm(ModuleSystemType::class, $system);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le variant du module a été enregistré');

            return $this->redirectToRoute('admin_module_system_view', ['system' => $system->getId()]);
        }

        return $this->render('admin/module-system/edit.html.twig', [
            'form' => $form->createView(),
            'system' => $system,
        ]);
    }
}
