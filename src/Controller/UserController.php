<?php

namespace App\Controller;

use App\DTO\TimeInterval;
use App\Entity\Module;
use App\Entity\User;
use App\Entity\UserModule;
use App\Entity\VariantStat;
use App\Perun\Repository\DataTypeRepository;
use App\Perun\Repository\LogStatRepository;
use App\Repository\ModuleRepository;
use App\Repository\VariantStatRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{user}", name="user_view")
     * @ParamConverter("user", options={"mapping": {"user": "nickname"}})
     */
    public function view(ModuleRepository $moduleRepository, VariantStatRepository $variantStatRepository, LogStatRepository $logStatRepository, DataTypeRepository $dataTypeRepository, User $user): Response
    {
        $data = [];

        $data['user'] = $user;
        $data['moduleTypes'] = Module::TYPES;
        $data['modules'] = $this->getDoctrine()->getRepository(Module::class)->findBy([], ['type' => 'asc', 'period' => 'desc', 'name' => 'asc']);
        $data['userModulesTypes'] = $this->getDoctrine()->getRepository(UserModule::class)->findByUserIndexedByTypeAndModule($user);

        $periodWeeks = 4;
        $period = new TimeInterval(sprintf('today -%d weeks', $periodWeeks), 'tomorrow');

        // stats over all
        $data['periodWeeks'] = $periodWeeks;
        $data['stats'] = $user->getPlayer() ? $logStatRepository->countTotals($user->getPlayer(), $period) : null;
        $data['mostPlayedAircraft'] = $user->getPlayer() ? $dataTypeRepository->findOneByPlayerAndBestTotalHours($user->getPlayer(), Module::TYPE_AIRCRAFT, $period) : null;
        $data['mostPlayedHelicopter'] = $user->getPlayer() ? $dataTypeRepository->findOneByPlayerAndBestTotalHours($user->getPlayer(), Module::TYPE_HELICOPTER, $period) : null;

        return $this->render('user/view.html.twig', $data);
    }

    /**
     * @Route("/{user}/stats", name="user_stats")
     * @ParamConverter("user", options={"mapping": {"user": "nickname"}})
     */
    public function stats(ModuleRepository $moduleRepository, VariantStatRepository $variantStatRepository, User $user): Response
    {
        $data = [];

        $data['user'] = $user;

        if (null !== $user->getPlayer()) {
            $data['stats'] = $this->getDoctrine()->getRepository(VariantStat::class)->countTotals($user->getPlayer());
            $data['statsVariants'] = $this->getDoctrine()->getRepository(VariantStat::class)->countTotalsByVariant($user->getPlayer());
        }

        return $this->render('user/stats.html.twig', $data);
    }
}
