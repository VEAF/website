<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    private string $website;

    public function __construct(string $website)
    {
        $this->website = $website;
    }

    /**
     * @Route("/register", name="register", methods={"GET", "POST"})
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        UserManager $userManager
    )
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']); // default ROLE

            if ('51eg' === $this->website) {
                $user->setSimDcs(true);
            }

            $userManager->save($user, true, true);
            $this->addFlash('success', 'Votre compte a été créé');

            $token = new UsernamePasswordToken($user, $password, 'main');
            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));

            return $this->redirectToRoute('app_login');
        }

        return $this->render(
            'registration/register.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
