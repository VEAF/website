<?php

namespace App\Controller\Admin;

use App\Entity\Url;
use App\Form\UrlType;
use App\Manager\ModuleManager;
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
 * @Route("/admin/url")
 */
class UrlController extends AbstractController
{
    public function getTable(): Table
    {
        $queryBuilder = $this->getDoctrine()->getRepository(Url::class)->createQueryBuilder('u')
            ->select('u');

        $booleanFilterChoices = ['Oui' => true, 'Non' => false];

        $table = (new Table())
            ->setId('admin_url_list')
            ->setPath($this->generateUrl('admin_url_list_ajax'))
            ->setTemplate('admin/url/_list.html.twig')
            ->setQueryBuilder($queryBuilder, 'u');

        $table
            ->addColumn(
                (new Column())->setLabel('Slug')
                    ->setSort(['u.slug' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('u.slug')
                        ->setName('u_slug')
                    )
            );

        $table
            ->addColumn(
                (new Column())->setLabel('Cible')
                    ->setSort(['u.target' => 'asc'])
                    ->setFilter((new Filter())
                        ->setField('u.target')
                        ->setName('u_target')
                    )
                    ->setDisplayCallback(function ($value, $row, $lines) {
                        return sprintf('<a href="%s">%s</a>', $value, substr($value, 0, min(40, strlen($value))));
                    })
                    ->setRaw(true)
            );

        return $table;
    }

    /**
     * @Route("", name="admin_url_list")
     */
    public function list(TableService $tableService): Response
    {
        return $this->render('admin/url/list.html.twig', [
            'table' => $tableService->createFormView($this->getTable()),
        ]);
    }

    /**
     * @Route("/_ajax", name="admin_url_list_ajax")
     */
    public function _listAction(TableService $tableService, Request $request)
    {
        return $tableService->handleRequest($this->getTable(), $request);
    }

    /**
     * @Route("/add", name="admin_url_add")
     * @Route("/{url}/edit", name="admin_url_edit")
     */
    public function edit(ModuleManager $moduleManager, Request $request, Url $url = null): Response
    {
        if (null === $url) {
            $url = new Url();
            $url->setCreatedAt(new \DateTime('now'));
        }
        $form = $this->createForm(UrlType::class, $url);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url->setUpdatedAt(new \DateTime('now'));

            $this->getDoctrine()->getManager()->persist($url);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'L\'url a été enregistrée');

            return $this->redirectToRoute('admin_url_view', ['url' => $url->getId()]);
        }

        return $this->render('admin/url/edit.html.twig', [
            'form' => $form->createView(),
            'url' => $url,
        ]);
    }

    /**
     * @Route("/{url}/delete", name="admin_url_delete")
     */
    public function delete(Request $request, Url $url): Response
    {
        $form = $this->createForm(FormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->remove($url);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'L\'url a été supprimée');

            return $this->redirectToRoute('admin_url_list');
        }

        return $this->render('admin/url/delete.html.twig', [
            'form' => $form->createView(),
            'url' => $url,
        ]);
    }

    /**
     * @Route("/{url}", name="admin_url_view")
     */
    public function view(Url $url): Response
    {
        return $this->render('admin/url/view.html.twig', [
            'url' => $url,
        ]);
    }
}
