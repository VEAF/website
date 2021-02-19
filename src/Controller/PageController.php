<?php

namespace App\Controller;

use App\Entity\Page;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Why PageController ? small hack to load routes from this controller
 */
class PageController extends AbstractController
{
    /**
     * @Route("/{path}", name="page")
     * @ParamConverter("page", options={"mapping": {"path": "path"}})
     *
     * @return Response
     */
    public function page(Page $page): Response
    {
        if (!$page->isEnabled()) {
            throw new NotFoundHttpException('page non trouvée');
        }

        return $this->render('page/page.html.twig', ['page' => $page]);
    }
}
