<?php

namespace App\Controller\Admin;

use App\Entity\User;
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
        $data = [];

        $data['total'] = [
            'users' => $this->getDoctrine()->getRepository(User::class)->count([]),
        ];
        return $this->render('admin/default/index.html.twig', $data);
    }
}
