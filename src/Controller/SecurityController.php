<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirect('http://localhost:5173/');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
    }

    /**
     * @throws RandomException
     * @throws TransportExceptionInterface
     */
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        UrlGeneratorInterface $urlGenerator,
    ): Response {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            if ($email) {
                $user = $userRepository->findOneBy(['email' => $email]);

                if ($user) {
                    $token = bin2hex(random_bytes(32));
                    $expiresAt = new \DateTimeImmutable('+1 hour');

                    $user->setResetPasswordToken($token);
                    $user->setResetPasswordTokenExpiresAt($expiresAt);

                    $em->flush();

                    $resetUrl = $urlGenerator->generate('app_reset_password', [
                        'token' => $token,
                    ], UrlGeneratorInterface::ABSOLUTE_URL);

                    $emailMessage = (new TemplatedEmail())
                        ->to(new Address($user->getEmail()))
                        ->subject('Réinitialisation de votre mot de passe ExelHunt')
                        ->htmlTemplate('emails/reset_password.html.twig')
                        ->context([
                            'resetUrl' => $resetUrl,
                            'user' => $user,
                        ]);

                    $mailer->send($emailMessage);
                }

                $this->addFlash('success', 'Si un compte existe avec cet email, un lien de réinitialisation a été envoyé.');

                return $this->redirectToRoute('app_forgot_password');
            }
        }

        return $this->render('security/forgot_password.html.twig');
    }

    #[Route('/reset-password/{token}', name: 'app_reset_password')]
    public function resetPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
    ): Response {
        $user = $userRepository->findOneBy(['resetPasswordToken' => $token]);

        if (!$user || !$user->getResetPasswordTokenExpiresAt() || $user->getResetPasswordTokenExpiresAt() < new \DateTimeImmutable()) {
            $this->addFlash('danger', 'Ce lien de réinitialisation n’est plus valide.');

            return $this->redirectToRoute('app_forgot_password');
        }

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $passwordConfirm = $request->request->get('password_confirm');

            if (!$password || $password !== $passwordConfirm) {
                $this->addFlash('danger', 'Les mots de passe ne correspondent pas.');

                return $this->redirectToRoute('app_reset_password', ['token' => $token]);
            }

            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            $user->setResetPasswordToken(null);
            $user->setResetPasswordTokenExpiresAt(null);

            $em->flush();

            $this->addFlash('success', 'Votre mot de passe a été mis à jour. Vous pouvez vous connecter.');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.html.twig', [
            'token' => $token,
        ]);
    }
}
