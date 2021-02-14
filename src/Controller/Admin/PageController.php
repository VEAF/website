<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Entity\User;
use App\Form\PageType;
use App\Form\UserType;
use App\Manager\PageManager;
use App\Manager\UserManager;
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
 * @Route("/admin/page")
 */
class PageController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Page::class)->createQueryBuilder('p')
            ->select('p');

        $booleanFilterChoices = ['Oui' => true, 'Non' => false];

        $table = (new Table())
            ->setId('admin_user_list')
            ->setPath($this->generateUrl('admin_page_list_ajax'))
            ->setTemplate('admin/page/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'p');

        $table
            ->addColumn(
                (new Column())->setLabel('Route')
                    ->setSort(['p.route' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('p.route')
                        ->setName('p_route')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Titre')
                    ->setSort(['p.title' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('p.title')
                        ->setName('p_title')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Url')
                    ->setSort(['p.path' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('p.path')
                        ->setName('p_path')
                    )
            );


        $table
            ->addColumn(
                (new Column())->setLabel('Activée')
                    ->setSort(['p.enabled' => 'asc', 'p.title' => 'asc'])
                    ->setFilter((new FilterSelect())
                        ->setField('p.enabled')
                        ->setName('p_enabled')
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
     * @Route("", name="admin_page_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/page/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_page_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/add", name="admin_page_add")
     */
    public function add(PageManager $manager, Request $request): Response
    {
        $page = new Page();
        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($page, true, true);
            $this->addFlash('success', 'La page a été enregistrée');

            return $this->redirectToRoute('admin_page_view', ['page' => $page->getId()]);
        }

        return $this->render('admin/page/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{page}/edit", name="admin_page_edit")
     */
    public function edit(PageManager $manager, Request $request, Page $page): Response
    {
        $form = $this->createForm(PageType::class, $page);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->save($page, true, true);
            $this->addFlash('success', 'La page a été enregistrée');

            return $this->redirectToRoute('admin_page_view', ['page' => $page->getId()]);
        }

        return $this->render('admin/page/edit.html.twig', [
            'form' => $form->createView(),
            'page' => $page,
        ]);
    }

    /**
     * @Route("/{page}", name="admin_page_view")
     */
    public function view(Page $page): Response
    {
        return $this->render('admin/page/view.html.twig', ['page' => $page]);
    }

}
