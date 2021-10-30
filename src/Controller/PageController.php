<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Url;
use App\Security\Restriction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Why PageController ? small hack to load routes from this controller.
 */
class PageController extends AbstractController
{
    /**
     * Handle page (CMS) or link (url shortener).
     *
     * @Route("/{path}", name="page", requirements={"path"=".+"})
     */
    public function page(string $path, Restriction $restriction): Response
    {
        $link = $this->getDoctrine()->getRepository(Url::class)->findOneBySlug($path);
        if (null !== $link) {
            return new RedirectResponse($link->getTarget());
        }

        $page = $this->getDoctrine()->getRepository(Page::class)->findOneByPath($path);
        if (null === $page || !$page->isEnabled()) {
            throw new NotFoundHttpException('page non trouvée');
        }

        if (!$restriction->isGrantedToLevel($this->getUser(), $page->getRestriction())) {
            return $this->render('page/page_forbidden.html.twig', ['page' => $page]);
        }

        return $this->render('page/page.html.twig', ['page' => $page]);
    }
}
