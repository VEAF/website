<?php

namespace App\Controller\Admin\Dcs;

use App\Entity\Variant;
use App\Form\VariantType;
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
 * @Route("/admin/dcs/variant")
 */
class VariantController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Variant::class)->createQueryBuilder('v')
            ->select('v, m')
            ->leftJoin('v.module', 'm');

        $table = (new Table())
            ->setId('admin_dcs_variant_list')
            ->setPath($this->generateUrl('admin_dcs_variant_list_ajax'))
            ->setTemplate('admin/dcs/variant/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'v');

        $table
            ->addColumn(
                (new Column())->setLabel('Nom')
                    ->setSort(['v.name' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('v.name')
                        ->setName('v_name')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Code')
                    ->setSort(['v.code' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('v.code')
                        ->setName('v_code')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Module')
                    ->setSort(['m.name' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('m.name')
                        ->setName('m_name')
                    )
            );

        return $table;
    }

    /**
     * @Route("", name="admin_dcs_variant_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/dcs/variant/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_dcs_variant_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/{variant}", name="admin_dcs_variant_view")
     */
    public function view(Variant $variant): Response
    {
        return $this->render('admin/dcs/variant/view.html.twig', [
            'variant' => $variant,
        ]);
    }

    /**
     * @Route("/{variant}/edit", name="admin_dcs_variant_edit")
     */
    public function edit(EntityManagerInterface $entityManager, Request $request, Variant $variant): Response
    {
        $form = $this->createForm(VariantType::class, $variant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le variant du module a été enregistré');

            return $this->redirectToRoute('admin_dcs_variant_view', ['variant' => $variant->getId()]);
        }

        return $this->render('admin/dcs/variant/edit.html.twig', [
            'form' => $form->createView(),
            'variant' => $variant,
        ]);
    }
}
