<?php

namespace App\Controller;

use App\Entity\Server;
use App\Form\ServerControlType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/server")
 */
class ServerController extends AbstractController
{
    /**
     * @Route("/{server}/control", name="server_control")
     * @Security("is_granted('EDIT_CONTROL', server)")
     * @ParamConverter("server", options={"mapping": {"server": "code"}})
     */
    public function _control(Request $request, Server $server): Response
    {
        $form = $this->createForm(ServerControlType::class, $server);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Changements appliqués');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('perun_instance', ['server' => $server->getCode()]);
        }

        return $this->render('server/_control.html.twig',
            [
                'form' => $form->createView(),
                'server' => $server,
            ]
        );
    }
}
