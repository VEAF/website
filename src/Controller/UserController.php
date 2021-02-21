<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\User;
use App\Entity\UserModule;
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
    public function view(ModuleRepository $moduleRepository, VariantStatRepository $variantStatRepository, User $user): Response
    {
        $data = [];

        $data['user'] = $user;
        $data['moduleTypes'] = Module::TYPES;
        $data['modules'] = $this->getDoctrine()->getRepository(Module::class)->findBy([], ['type' => 'asc', 'name' => 'asc']);
        $data['userModulesTypes'] = $this->getDoctrine()->getRepository(UserModule::class)->findByUserIndexedByTypeAndModule($user);
        $data['totalHours'] = $user->getPlayer() ? $variantStatRepository->countTotalHoursByPlayer($user->getPlayer()) : 0;
        $data['morePlayedAircraft'] = $user->getPlayer() ? $moduleRepository->findOneByPlayerAndBestTotalHours($user->getPlayer(), Module::TYPE_AIRCRAFT) : null;
        $data['morePlayedHelicopter'] = $user->getPlayer() ? $moduleRepository->findOneByPlayerAndBestTotalHours($user->getPlayer(), Module::TYPE_HELICOPTER) : null;

        return $this->render('user/view.html.twig', $data);
    }
}
