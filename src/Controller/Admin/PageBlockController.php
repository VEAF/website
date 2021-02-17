<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Entity\PageBlock;
use App\Form\PageBlockType;
use App\Manager\PageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/page-block")
 */
class PageBlockController extends AbstractController
{
    /**
     * @Route("/add/{page}/{number}", name="admin_page_block_add")
     */
    public function add(PageManager $manager, Request $request, Page $page, int $number): Response
    {
        $block = new PageBlock();
        $block->setType(PageBlock::TYPE_MARKDOWN);
        $block->setPage($page);
        $block->setNumber($number);
        $form = $this->createForm(PageBlockType::class, $block);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->saveBlock($block);
            $this->addFlash('success', 'Le block a été enregistré');

            return $this->redirectToRoute('admin_page_view', ['page' => $page->getId()]);
        }

        return $this->render('admin/page-block/edit.html.twig', [
            'form' => $form->createView(),
            'block' => $block,
        ]);
    }

    /**
     * @Route("/{block}/edit", name="admin_page_block_edit")
     */
    public function edit(PageManager $manager, Request $request, PageBlock $block): Response
    {
        $form = $this->createForm(PageBlockType::class, $block);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->saveBlock($block);
            $this->addFlash('success', 'Le block a été enregistré');

            return $this->redirectToRoute('admin_page_view', ['page' => $block->getPage()->getId()]);
        }

        return $this->render('admin/page-block/edit.html.twig', [
            'form' => $form->createView(),
            'block' => $block,
        ]);
    }
}
