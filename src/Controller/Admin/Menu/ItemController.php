<?php

namespace App\Controller\Admin\Menu;

use App\Entity\Menu\Item;
use App\Form\MenuItemType;
use App\Manager\Menu\ItemManager;
use App\Security\Restriction;
use Kilik\TableBundle\Components\Column;
use Kilik\TableBundle\Components\Filter;
use Kilik\TableBundle\Components\FilterSelect;
use Kilik\TableBundle\Components\Table;
use Kilik\TableBundle\Services\TableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/menu/item")
 */
class ItemController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Item::class)->createQueryBuilder('i')
            ->select("i, m, case when m.id is null then i.position else CONCAT(m.position, '.', i.position) end AS pos")
            ->leftJoin('i.menu', 'm');

        $booleanFilterChoices = ['Oui' => true, 'Non' => false];

        $table = (new Table())
            ->setId('admin_menu_item_list')
            ->setPath($this->generateUrl('admin_menu_item_list_ajax'))
            ->setTemplate('admin/menu/item/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'i');

        $table
            ->addColumn(
                (new Column())->setLabel('Position')
                    ->setSort(['pos' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('pos')
                        ->setName('pos')
                        ->setHaving(true)
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Menu')
                    //->setSort(['m.position' => 'asc', 'i.position' => 'asc'])
                    ->setSort(['m.label' => 'asc', 'i.label' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('m.label')
                        ->setName('m_label')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Elément')
                    ->setSort(['i.label' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('i.label')
                        ->setName('i_label')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Type')
                    ->setSort(['i.type' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('i.type')
                        ->setName('i_type')
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        /** @var Item $item */
                        $item = $row['object'];

                        return $item->getTypeAsString();
                    })
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Activé')
                    ->setSort(['i.enabled' => 'asc', 'pos' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('i.enabled')
                        ->setName('i_enabled')
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
                (new Column())->setLabel('Restriction')
                    ->setSort(['i.enabled' => 'asc', 'pos' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('i.restriction')
                        ->setName('i_restriction')
                        ->setChoices(array_flip(Restriction::LEVELS))
                        ->setPlaceholder('-')
                        ->disableTranslation() // disable translations of placeholder and values
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        if(isset(Restriction::LEVELS[$value])) {
                            return Restriction::LEVELS[$value];
                        }
                        else {
                            return $value;
                        }
                    })
            );

        return $table;
    }

    /**
     * @Route("", name="admin_menu_item_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/menu/item/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_menu_item_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/add", name="admin_menu_item_add")
     * @Route("/{item}/edit", name="admin_menu_item_edit")
     */
    public function edit(ItemManager $itemManager, Request $request, Item $item = null): Response
    {
        if (null === $item) {
            $item = new Item();
        }
        $form = $this->createForm(MenuItemType::class, $item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itemManager->save($item, true);

            $this->addFlash('success', 'L\'élément du menu a été enregistré');

            return $this->redirectToRoute('admin_menu_item_view', ['item' => $item->getId()]);
        }

        return $this->render('admin/menu/item/edit.html.twig', [
            'form' => $form->createView(),
            'item' => $item,
        ]);
    }

    /**
     * @Route("/{item}/delete", name="admin_menu_item_delete")
     */
    public function delete(ItemManager $itemManager, Request $request, Item $item): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $itemManager->delete($item, true);

            $this->addFlash('success', 'L\'élément du menu a été supprimé');

            return $this->redirectToRoute('admin_menu_item_list');
        }

        return $this->render('admin/menu/item/delete.html.twig', [
            'form' => $form->createView(),
            'item' => $item,
        ]);
    }

    /**
     * @Route("/{item}", name="admin_menu_item_view")
     */
    public function view(Item $item): Response
    {
        return $this->render('admin/menu/item/view.html.twig', [
            'item' => $item,
        ]);
    }
}
