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
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/{user}/stats/period/{periodName}", name="user_stats_period")
     * @Route("/{user}/stats/period/{start}/{end}", name="user_stats_period_custom")
     * @ParamConverter("user", options={"mapping": {"user": "nickname"}})
     */
    public function stats(Request $request, ?string $periodName, ModuleRepository $moduleRepository, VariantStatRepository $variantStatRepository, LogStatRepository $logStatRepository, DataTypeRepository $dataTypeRepository, User $user, ?\DateTime $start, ?\DateTime $end): Response
    {
        $data = [];

        $periods = [
            'today' => new TimeInterval('today', 'today'),
            'yesterday' => new TimeInterval('yesterday', 'yesterday'),
            'this-month' => new TimeInterval('first day of this month midnight', 'today'),
            'last-month' => new TimeInterval('first day of this month midnight -1 month', 'last day of previous month midnight'),
            'this-year' => new TimeInterval('first day of January midnight', 'today'),
            // note: bug of end of last-year (un day too far)
            'last-year' => new TimeInterval('first day of January midnight -1 year', 'first day of January midnight -1 day'),
            'all-time' => new TimeInterval(),
        ];

        // @todo use translations
        $periodsTranslations = [
            'today' => "Aujourd'hui",
            'yesterday' => "Hier",
            'this-month' => "Ce mois",
            'last-month' => "Le mois dernier",
            'this-year' => "Cette année",
            'last-year' => "L'année dernière",
            'all-time' => "Depuis toujours",
        ];

        switch ($request->get('_route')) {
            case 'user_stats':
                return $this->redirectToRoute('user_stats_period', ['periodName' => 'this-month', 'user' => $user->getNickname()]);
                break;
            case 'user_stats_period':
                if (!isset($periods[$periodName])) {
                    return $this->redirectToRoute('user_stats_period', ['periodName' => 'this-month', 'user' => $user->getNickname()]);
                } else {
                    $period = $periods[$periodName];
                }
                break;
            case 'user_stats_period_custom':
                $period = new TimeInterval();
                $period->setStart($start);
                $period->setEnd($end);
                break;
        }

        if (!null === $period->getEnd()) {
            $period->getEnd()->modify('+1 day -1 second');
        }

        $data['user'] = $user;
        if (null !== $user->getPlayer()) {
            $data['period'] = $period;
            $data['periods'] = $periods;
            $data['periodsTranslations'] = $periodsTranslations;
            $data['periodName'] = $periodName;
            $data['stats'] = $logStatRepository->countTotals($user->getPlayer(), $period);
            $data['statsTypes'] = $dataTypeRepository->countTotalsByType($user->getPlayer(), $period);
        }

        return $this->render('user/stats.html.twig', $data);
    }
}
