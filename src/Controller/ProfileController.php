<?php

namespace App\Controller;

use App\Entity\Module;
use App\Entity\User;
use App\Entity\UserModule;
use App\Form\ProfileType;
use App\Manager\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Protected with security firewall.
 *
 * @Route("/profile")
 * @Security("is_granted('ROLE_USER')")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("", name="profile")
     */
    public function index(): Response
    {
        $data = [];

        // all known modules
        $data['moduleTypes'] = Module::TYPES;
        $data['modules'] = $this->getDoctrine()->getRepository(Module::class)->findBy([], ['type' => 'asc', 'period' => 'desc', 'name' => 'asc']);
        $data['myModules'] = $this->getDoctrine()->getRepository(UserModule::class)->findByUserIndexedByModule($this->getUser());
        $data['minCadetsFlights'] = User::CADET_MIN_FLIGHTS;

        return $this->render('profile/index.html.twig', $data);
    }

    /**
     * @Route("/edit", name="profile_edit")
     */
    public function edit(Request $request, UserManager $userManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userManager->save($user, true, true);
            $this->addFlash('success', 'Mon profil a été enregistré');

            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/module/{module}/level/{level}", name="profile_change_level")
     */
    public function changeLevel(Request $request, SerializerInterface $serializer, Module $module, int $level): Response
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        $userModule = $this->getDoctrine()->getRepository(UserModule::class)->findOneBy(['module' => $module, 'user' => $this->getUser()]);
        if (null === $userModule) {
            $userModule = new UserModule();
            $userModule->setModule($module);
            $userModule->setUser($this->getUser());
        }
        if (UserModule::LEVEL_UNKNOWN !== $level) {
            $userModule->setActive(true);
            $this->getDoctrine()->getManager()->persist($userModule);
        } elseif (null !== $userModule->getId() && UserModule::LEVEL_UNKNOWN === $level) {
            $userModule->setActive(false);
            $this->getDoctrine()->getManager()->remove($userModule);
        }
        // restriction: Instructor only for members
        if (!$userModule->getUser()->isMember() && UserModule::LEVEL_INSTRUCTOR === $level) {
            throw new AccessDeniedHttpException();
        }
        $userModule->setLevel($level);
        $this->getDoctrine()->getManager()->flush();

        return new Response($serializer->serialize($userModule, 'json', ['groups' => ['user_module', 'module']]), Response::HTTP_ACCEPTED, ['content-type' => 'application/json']);
    }

    /**
     * @Route("/module/{module}/active/{active}", name="profile_change_active")
     */
    public function changeActive(Request $request, SerializerInterface $serializer, Module $module, bool $active): Response
    {
        if (!$request->isXmlHttpRequest()) {
            throw new AccessDeniedHttpException();
        }

        $userModule = $this->getDoctrine()->getRepository(UserModule::class)->findOneBy(['module' => $module, 'user' => $this->getUser()]);
        if (null === $userModule) {
            $userModule = new UserModule();
            $userModule->setModule($module);
            $userModule->setUser($this->getUser());
            if ($module->isWithLevel()) {
                $userModule->setLevel(UserModule::LEVEL_ROOKIE);
            } else {
                $userModule->setLevel(UserModule::LEVEL_MISSION);
            }
            $this->getDoctrine()->getManager()->persist($userModule);
        }
        $userModule->setActive($active);
        $this->getDoctrine()->getManager()->flush();

        return new Response($serializer->serialize($userModule, 'json', ['groups' => ['user_module', 'module']]), Response::HTTP_ACCEPTED, ['content-type' => 'application/json']);
    }
}
