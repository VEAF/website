<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("", name="admin_index")
     */
    public function index(): Response
    {
        return $this->render('admin/default/index.html.twig', [
        ]);
    }
}
