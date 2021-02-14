<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    public function page(Page $page): Response
    {
        if (!$page->isEnabled()) {
            throw new NotFoundHttpException('page non trouvée');
        }
        return $this->render('page/page.html.twig', ['page' => $page]);
    }
}
