<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/fr/association/le-bureau", name="office")
     */
    public function office(): Response
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        $data = [
            'president' => $userRepo->findOneByStatus(User::STATUS_PRESIDENT),
            'presidentDeputy' => $userRepo->findOneByStatus(User::STATUS_PRESIDENT_DEPUTY),
            'treasurer' => $userRepo->findOneByStatus(User::STATUS_TREASURER),
            'treasurerDeputy' => $userRepo->findOneByStatus(User::STATUS_TREASURER_DEPUTY),
            'secretary' => $userRepo->findOneByStatus(User::STATUS_SECRETARY),
            'secretaryDeputy' => $userRepo->findOneByStatus(User::STATUS_SECRETARY_DEPUTY),
        ];

        return $this->render('default/office.html.twig', $data);
    }
}
