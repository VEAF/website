<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\User;
use App\Entity\UserModule;
use App\Form\MissionMakerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MissionMakerController extends AbstractController
{
    /**
     * @Route("/mission-maker", name="mission_maker")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(MissionMakerType::class);

        $form->handleRequest($request);

        $data = [];

        if ($form->isSubmitted() && $form->isValid()) {

            $map = $form->getData()['map'];
            $group = $form->getData()['group'];
            $mapUsers = $this->getDoctrine()->getRepository(UserModule::class)
                ->findByModuleAndUserStatus($map, User::getGroupStatuses($group));

            $modules = [];

            foreach ($form->getData()['modules'] as $module) {
                $modules[$module->getId()] = $module;
            }

            /** @var array $usersModules (first level key user id, second level key module id */
            $usersModules = [];

            foreach ($mapUsers as $mapUser) {
                foreach ($this->getDoctrine()->getRepository(UserModule::class)
                             ->findByUserIndexedByModule($mapUser->getUser(), true, [UserModule::LEVEL_MISSION, UserModule::LEVEL_INSTRUCTOR]) as $userModule) {
                    $usersModules[$mapUser->getUser()->getId()][$userModule->getModule()->getId()] = $userModule;
                }
            }

            $data['map'] = $map;
            $data['modules'] = $modules;
            $data['mapUsers'] = $mapUsers;
            $data['usersModules'] = $usersModules;
        }

        $data['form'] = $form->createView();

        return $this->render('mission-maker/index.html.twig', $data);
    }
}
