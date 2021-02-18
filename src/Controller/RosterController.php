<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\User;
use App\Entity\UserModule;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        return $this->render('roster/index.html.twig', $data);
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

        return $this->render('roster/index.html.twig', $data);
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
            $data['aircrafts'] = $this->getDoctrine()->getRepository(Module::class)->findBy(['type' => Module::TYPE_AIRCRAFT], ['name' => 'asc']);
            $data['aircraftsCount'] = $this->getDoctrine()->getRepository(UserModule::class)->countUsersByModule(Module::TYPE_AIRCRAFT, User::getGroupStatuses($group));
        }

        return $this->render('roster/index.html.twig', $data);
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

        return $this->render('roster/index.html.twig', $data);
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

        return $this->render('roster/index.html.twig', $data);
    }
}
