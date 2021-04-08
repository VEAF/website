<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\PasswordRequestType;
use App\Manager\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/reset-password")
 */
class ResetPasswordController extends AbstractController
{
    private string $mailFrom;

    public function __construct(string $mailFrom)
    {
        $this->mailFrom = $mailFrom;
    }

    /**
     * @Route("", name="reset_password", methods={"GET", "POST"})
     */
    public function reset(
        Request $request,
        EntityManagerInterface $entityManager,
        \Swift_Mailer $mailer,
        UserManager $userManager
    ) {
        $form = $this->createForm(PasswordRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user instanceof User) {
                $token = bin2hex(random_bytes(32));
                $user->setPasswordRequestToken($token);
                $user->setPasswordRequestExpiredAt(new \DateTime('+1 day'));
                $userManager->save($user, true, true);
                // send your email with SwiftMailer or anything else here
                $this->addFlash('success', 'Si l\'adresse email est correcte, vous allez recevoir un email');

                $passwordResetUrl = $this->generateUrl('reset_password_confirm', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                $message = (new \Swift_Message(sprintf('%s Site Web - reset de mon mot de passe', strtoupper($this->getParameter('website')))))
                    ->setFrom($this->mailFrom)
                    ->setTo($user->getEmail())
                    // html version
                    ->setBody(
                        $this->renderView(
                            'email/reset.html.twig',
                            [
                                'nickname' => $user->getNickname(),
                                'passwordResetUrl' => $passwordResetUrl,
                            ]
                        ),
                        'text/html'
                    )
                    // txt version, for client mail before 1971 ...
                    ->addPart(
                        $this->renderView(
                            'email/reset.txt.twig',
                            [
                                'nickname' => $user->getNickname(),
                                'passwordResetUrl' => $passwordResetUrl,
                            ]
                        ),
                        'text/plain'
                    );

                $mailer->send($message);
            }

            return $this->redirectToRoute('reset_password');
        }

        return $this->render('reset-password/reset.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/confirm/{token}", name="reset_password_confirm", methods={"GET", "POST"})
     */
    public function confirm(
        Request $request,
        string $token,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $encoder,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session,
        UserManager $userManager
    ) {
        $now = new \DateTime('now');
        $user = $entityManager->getRepository(User::class)->findOneBy(['passwordRequestToken' => $token]);
        $expired = false;

        if (null !== $user && null !== $user->getPasswordRequestExpiredAt() && $now->getTimestamp() > $user->getPasswordRequestExpiredAt()->getTimestamp()) {
            $expired = true;
        }

        if (!$token || !$user instanceof User || $expired) {
            $this->addFlash('error', 'Lien invalide, jeton incorrect ou trop vieux.');

            return $this->redirectToRoute('reset_password');
        }

        $form = $this->createForm(NewPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('password')->getData();
            $password = $encoder->encodePassword($user, $plainPassword);
            $user->setPassword($password);
            $user->setPasswordRequestToken(null);
            $userManager->save($user, true, true);

            $token = new UsernamePasswordToken($user, $password, 'main');
            $tokenStorage->setToken($token);
            $session->set('_security_main', serialize($token));

            $this->addFlash('success', 'Votre mot de passe a été modifié');

            return $this->redirectToRoute('home');
        }

        return $this->render('reset-password/confirm.html.twig', ['form' => $form->createView()]);
    }
}
