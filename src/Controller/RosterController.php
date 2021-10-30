<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\User;
use App\Entity\UserModule;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Criteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/roster")
 */
class RosterController extends AbstractController
{
    const GROUPS = [
        'all' => 'Tout le monde',
        'cadets' => 'Cadets',
        'members' => 'Membres',
    ];

    const TABS = [
        'pilots' => 'Pilotes',
        'aircrafts' => 'Avions',
        'helicopters' => 'Hélicoptères',
        'maps' => 'Cartes',
        'specials' => 'Spéciaux',
    ];

    /**
     * @Route("", name="roster")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('roster_pilots', ['group' => 'all']);
    }

    private function roster(array $data): Response
    {
        // pilots stats
        $data['stats'] = [
            'pilots' => $this->getDoctrine()->getRepository(User::class)->count([]),
            'cadets' => $this->getDoctrine()->getRepository(User::class)->countByStatus(User::STATUS_CADET),
            'members' => $this->getDoctrine()->getRepository(User::class)->countByStatus(User::STATUSES_MEMBER),
        ];

        return $this->render('roster/index.html.twig', $data);
    }

    /**
     * @Route("/pilots/{group}", name="roster_pilots")
     */
    public function pilots(string $group = null): Response
    {
        if (!isset(self::GROUPS[$group])) {
            return $this->redirectToRoute('roster_pilots', ['group' => 'all']);
        }

        $data = [];
        $data['groupSelected'] = $group;
        $data['groups'] = self::GROUPS;
        $data['tabs'] = self::TABS;
        $data['tab'] = 'pilots';
        $data['pilots'] = $this->getDoctrine()->getRepository(User::class)->findByUserStatus(User::getGroupStatuses($group));
        $data['cadetsNeedPresentation'] = $this->getDoctrine()->getRepository(User::class)->count(['status' => User::getGroupStatuses($group), 'needPresentation' => true]);

        $criteria = new Criteria();
        $criteria->andWhere(Criteria::expr()->in('status', User::getGroupStatuses(User::GROUP_CADETS)));
        $criteria->andWhere(Criteria::expr()->gte('cadetFlights', User::CADET_MIN_FLIGHTS));
        $criteria->andWhere(Criteria::expr()->eq('needPresentation', false));

        $data['cadetsReady'] = $this->getDoctrine()->getRepository(User::class)->matching($criteria)->count();

        return $this->roster($data);
    }

    /**
     * @Route("/maps/{group}", name="roster_maps")
     * @Route("/map/{map}/{group}", name="roster_map")
     * @ParamConverter("map", options={"mapping": {"map": "code"}})
     */
    public function maps(Module $map = null, string $group = null): Response
    {
        if (!isset(self::GROUPS[$group])) {
            return $this->redirectToRoute('roster_maps', ['group' => 'all']);
        }

        $data = [];
        $data['groupSelected'] = $group;
        $data['groups'] = self::GROUPS;
        $data['tabs'] = self::TABS;
        $data['tab'] = 'maps';
        if (null !== $map) {
            $data['selectedMap'] = $map;
            $data['pilots_modules'] = $this->getDoctrine()->getRepository(UserModule::class)->findByModuleAndUserStatus($map, User::getGroupStatuses($group));
            $data['module'] = $map;
        } else {
            $data['maps'] = $this->getDoctrine()->getRepository(Module::class)->findBy(['type' => Module::TYPE_MAP], ['name' => 'asc']);
            $data['mapsCount'] = $this->getDoctrine()->getRepository(UserModule::class)->countUsersByModule(Module::TYPE_MAP, User::getGroupStatuses($group));
        }

        return $this->roster($data);
    }

    /**
     * @Route("/aircrafts/{group}", name="roster_aircrafts")
     * @Route("/aircraft/{aircraft}/{group}", name="roster_aircraft")
     * @ParamConverter("aircraft", options={"mapping": {"aircraft": "code"}})
     */
    public function aircrafts(Module $aircraft = null, string $group = null): Response
    {
        if (!isset(self::GROUPS[$group])) {
            return $this->redirectToRoute('roster_aircrafts', ['group' => 'all']);
        }

        $data = [];
        $data['groupSelected'] = $group;
        $data['groups'] = self::GROUPS;
        $data['tabs'] = self::TABS;
        $data['tab'] = 'aircrafts';

        if (null !== $aircraft) {
            $data['selectedAircraft'] = $aircraft;
            $data['pilots_modules'] = $this->getDoctrine()->getRepository(UserModule::class)->findByModuleAndUserStatus($aircraft, User::getGroupStatuses($group));
            $data['module'] = $aircraft;
        } else {
            $data['aircrafts'] = $this->getDoctrine()->getRepository(Module::class)->findBy(['type' => Module::TYPE_AIRCRAFT], ['period' => 'desc', 'name' => 'asc']);
            $data['aircraftsCount'] = $this->getDoctrine()->getRepository(UserModule::class)->countUsersByModule(Module::TYPE_AIRCRAFT, User::getGroupStatuses($group));
        }

        return $this->roster($data);
    }

    /**
     * @Route("/helicopters/{group}", name="roster_helicopters")
     * @Route("/helicopter/{helicopter}/{group}", name="roster_helicopter")
     * @ParamConverter("helicopter", options={"mapping": {"helicopter": "code"}})
     */
    public function helicopters(Module $helicopter = null, string $group = null): Response
    {
        if (!isset(self::GROUPS[$group])) {
            return $this->redirectToRoute('roster_helicopters', ['group' => 'all']);
        }

        $data = [];
        $data['groupSelected'] = $group;
        $data['groups'] = self::GROUPS;
        $data['tabs'] = self::TABS;
        $data['tab'] = 'helicopters';

        if (null !== $helicopter) {
            $data['selectedHelicopter'] = $helicopter;
            $data['pilots_modules'] = $this->getDoctrine()->getRepository(UserModule::class)->findByModuleAndUserStatus($helicopter, User::getGroupStatuses($group));
            $data['module'] = $helicopter;
        } else {
            $data['helicopters'] = $this->getDoctrine()->getRepository(Module::class)->findBy(['type' => Module::TYPE_HELICOPTER], ['name' => 'asc']);
            $data['helicoptersCount'] = $this->getDoctrine()->getRepository(UserModule::class)->countUsersByModule(Module::TYPE_HELICOPTER, User::getGroupStatuses($group));
        }

        return $this->roster($data);
    }

    /**
     * @Route("/specials/{group}", name="roster_specials")
     * @Route("/special/{special}/{group}", name="roster_special")
     * @ParamConverter("special", options={"mapping": {"special": "code"}})
     */
    public function specials(Module $special = null, string $group = null): Response
    {
        if (!isset(self::GROUPS[$group])) {
            return $this->redirectToRoute('roster_specials', ['group' => 'all']);
        }

        $data = [];
        $data['groupSelected'] = $group;
        $data['groups'] = self::GROUPS;
        $data['tabs'] = self::TABS;
        $data['tab'] = 'specials';

        if (null !== $special) {
            $data['selectedSpecial'] = $special;
            $data['pilots_modules'] = $this->getDoctrine()->getRepository(UserModule::class)->findByModuleAndUserStatus($special, User::getGroupStatuses($group));
            $data['module'] = $special;
        } else {
            $data['specials'] = $this->getDoctrine()->getRepository(Module::class)->findBy(['type' => Module::TYPE_SPECIAL], ['name' => 'asc']);
            $data['specialsCount'] = $this->getDoctrine()->getRepository(UserModule::class)->countUsersByModule(Module::TYPE_SPECIAL, User::getGroupStatuses($group));
        }

        return $this->roster($data);
    }

    /**
     * @Route("/zombies/{format}", name="roster_zombies", defaults={"format": "html"})
     */
    public function zombies(string $format = 'html', UserRepository $userRepository)
    {
        $zombies = $userRepository->findZombies();

        $output = new BufferedOutput();

        if ('txt' === $format) {
            $table = new Table($output);
            $table->setHeaders(['nickname', 'createdAt', 'needPresentation', 'lastEventDays', 'lastOnlineSession', 'lastOnlineSessionDays']);
            foreach ($zombies as $zombie) {
                $lastEventDelay = null;
                foreach ($zombie->getRecruitmentEvents() as $event) {
                    $delay = time() - $event->getCreatedAt()->getTimestamp();
                    if (null === $lastEventDelay || $delay < $lastEventDelay) {
                        $lastEventDelay = $delay;
                    }
                }

                $lastOnlineSession = null;
                $lastOnlineSessionDelay = null;
                if (null !== $zombie->getPerunPlayer()) {
                    $lastOnlineSession = $zombie->getPerunPlayer()->getUpdated();
                    $lastOnlineSessionDelay = round((time() - $zombie->getPerunPlayer()->getUpdated()->getTimestamp()) / (24 * 3600));
                }
                $table->addRow([$zombie->getNickname(), $zombie->getCreatedAt()->format('d/m/Y'), $zombie->getNeedPresentation(), null != $lastEventDelay ? round($lastEventDelay / (24 * 3600)) : '', $lastOnlineSession ? $lastOnlineSession->format('d/m/Y') : '-', $lastOnlineSessionDelay ? $lastOnlineSessionDelay : '-']);
            }
            $table->render();

            return new Response($output->fetch(), Response::HTTP_OK, ['content-type' => 'text/plain']);
        } else {
            // @todo add a web rendering ?
            // return $this->render('roster/zombies.html.twig', ['zombies' => $zombies]);
            throw new \Exception('WIP');
        }
    }

    /**
     * @Route("/_specs/{module}", name="roster_module_specs")
     */
    public function moduleSpecs(Module $module): Response
    {
        return $this->render('roster/_module_specs.html.twig', ['module' => $module]);
    }
}
