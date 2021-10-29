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
     * @Route("/mission-maker/export/csv", name="mission_maker_export_csv")
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

            if (count($form->getData()['modules'])) {
                foreach ($form->getData()['modules'] as $module) {
                    $modules[$module->getId()] = $module;
                }
            } else {
                $filter = ['type' => Module::TYPE_AIRCRAFT];
                if (Module::PERIOD_NONE != $form->getData()['period']) {
                    $filter['period'] = $form->getData()['period'];
                }
                $modules = $this->getDoctrine()->getRepository(Module::class)->findBy($filter, ['name' => 'asc']);
                $modules = array_merge($modules, $this->getDoctrine()->getRepository(Module::class)->findByType(Module::TYPE_HELICOPTER));
            }

            /** @var array $usersModules (first level key user id, second level key module id */
            $usersModules = [];

            foreach ($mapUsers as $mapUser) {
                foreach ($this->getDoctrine()->getRepository(UserModule::class)
                             ->findByUserIndexedByModule($mapUser->getUser(), true, [UserModule::LEVEL_MISSION, UserModule::LEVEL_INSTRUCTOR]) as $userModule) {
                    if (isset($modules[$userModule->getModule()->getId()])) {
                        $usersModules[$mapUser->getUser()->getId()][$userModule->getModule()->getId()] = $userModule;
                    }
                }
            }

            // remove all users without modules
            foreach ($mapUsers as $keyUser => $mapUser) {
                if (!isset($usersModules[$mapUser->getUser()->getId()])) {
                    unset($mapUsers[$keyUser]);
                }
            }

            if ('mission_maker_export_csv' === $request->get('_route')) {
                // export CSV
                $lines = [];
                $line = [];
                $line[] = 'joueurs / modules';
                foreach ($modules as $module) {
                    $line[] = $module->getName();
                }
                $lines[] = $line;

                foreach ($mapUsers as $mapUser) {
                    $line = [];
                    $line[] = $mapUser->getUser()->getNickname();
                    foreach ($modules as $module) {
                        if (isset($usersModules[$mapUser->getUser()->getId()][$module->getId()])) {
                            $userModule = $usersModules[$mapUser->getUser()->getId()][$module->getId()];
                            switch ($userModule->getLevel()) {
                                case UserModule::LEVEL_INSTRUCTOR:
                                    $line[] = 'i';
                                    break;
                                case UserModule::LEVEL_MISSION:
                                    $line[] = 'm';
                                    break;
                                case UserModule::LEVEL_ROOKIE:
                                    $line[] = 'r';
                                    break;
                                case UserModule::LEVEL_UNKNOWN:
                                default:
                                    $line[] = 'u';
                                    break;
                            }
                        } else {
                            $line[] = '';
                        }
                    }
                    $lines[] = $line;
                }

                $response = new Response(implode(PHP_EOL, array_map(
                    function ($line) {
                        return implode(';', $line);
                    }, $lines)));
                $response->headers->set('Content-Type', 'text/csv');
                $response->headers->set('Content-Disposition', 'attachment; filename="mission-maker.csv"');

                return $response;
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
