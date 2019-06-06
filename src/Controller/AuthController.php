<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\PasswordResetFormType;
use App\Form\RegistrationFormType;
use App\Form\ResetPasswordFormType;
use App\Service\MailerService;
use App\Service\RecaptchaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class AuthController extends AbstractController
{
    /**
     * Users registration action.
     *
     * @param string $_locale
     * @param Request $request Current request.
     * @param UserPasswordEncoderInterface $passwordEncoder Password encoder.
     * @param MailerService $mailer
     * @param RecaptchaService $recaptcha
     * @param TranslatorInterface $translator
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function register(
        string $_locale,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        MailerService $mailer,
        RecaptchaService $recaptcha,
        TranslatorInterface $translator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recaptchaResponse = $recaptcha->checkRecaptcha($request);

            // Check recaptcha.
            if (!$recaptchaResponse['success']) {
                if (in_array('missing-input-response', $recaptchaResponse['errors'])) {
                    $recaptchaValidation = 'validation.empty_recaptcha';
                } else {
                    $recaptchaValidation = 'validation.invalid_recaptcha';
                }

                // Add recaptcha error.
                $form->get('recaptcha')->addError(new FormError($translator->trans($recaptchaValidation, [], 'validators')));
            } else {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );

                $entityManager = $this->getDoctrine()->getManager();

                // Save user in database.
                $entityManager->persist($user);
                $entityManager->flush();

                // Generate user verification token.
                $uniqToken = uniqid('verify', true);

                // Create new Token object.
                $token = new Token();
                $token->setUser($user)->setToken($uniqToken);

                // Save token in db.
                $entityManager->persist($token);
                $entityManager->flush();

                // Send welcome message.
                $mailer->sendMessage(
                    [$user->getEmail() => $user->getFirstName() . ' ' . $user->getLastName()],
                    'Welcome to Informatics.Ge',
                    'email/welcome.' . $_locale . '.html.twig',
                    [
                        'name' => $user->getFirstName(),
                        'surname' => $user->getLastName(),
                        'token' => $token->getToken(),
                        'expiration' => $token->getExpiration()->format('d.m.Y H:i:s')
                    ]
                );

                // Add flush message.
                $this->addFlash( 'success', 'flush.success_registration');

                $redirection = $this->redirectToRoute('index');

                return $redirection;
            }

        }

        return $this->render(
            'default/register.html.twig',
            [
                'registrationForm' => $form->createView(),
            ]
        );
    }

    /**
     * Users login action.
     *
     * @param string $_locale
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(string $_locale, AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginFormType::class);

        return $this->render(
            'default/login.html.twig',
            [
                'lastUsername' => $lastUsername,
                'error' => $error,
                'loginForm' => $form->createView(),
            ]
        );

    }

    /**
     * Verify user email.
     *
     * @param string $_locale
     * @param $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function verify(string $_locale, $token)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $tokenRepo = $doctrine->getRepository(Token::class);
        $tokenObj = $tokenRepo->findOneBy(['token' => $token]);

        if ('verify' === substr($token, 0, 6)
            && $tokenObj
            && (new \DateTime()) <= $tokenObj->getExpiration()
        ) {
            $userRepo = $doctrine->getRepository(User::class);
            $user = $userRepo->find($tokenObj->getUser());

            // Verify user.
            $user->setVerified(true);
            $em->persist($user);
            $em->flush();

            // Remove token from db.
            $em->remove($tokenObj);
            $em->flush();
        }

        // Add flush message.
        $this->addFlash( 'success', 'flush.success_verification');

        $redirection = $this->redirectToRoute('index');

        return $redirection;
    }

    /**
     * Reset user password.
     *
     * @param string $_locale
     * @param Request $request
     *
     * @param MailerService $mailer
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function resetPassword(string $_locale, Request $request, MailerService $mailer)
    {
        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get username from form.
            $username = $form->get('username_or_email')->getData();

            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();

            $userRepo = $doctrine->getRepository(User::class);
            $tokenRepo = $doctrine->getRepository(Token::class);

            // Get user.
            $user = $userRepo->findOneBy(['username' => $username]) ?:
                $userRepo->findOneBy(['email' => $username]);

            if ($user) {
                // Remove old token.
                $oldToken = $tokenRepo->findOneBy(['user' => $user->getId()]);
                if ($oldToken && 'reset' === substr($oldToken->getToken(), 0, 5)
                ) {
                    $em->remove($oldToken);
                    $em->flush();
                }

                // Generate new token.

                $uniqToken = uniqid('reset', true);

                $token = new Token();
                $token->setUser($user)->setToken($uniqToken);

                // Save token in db.
                $em->persist($token);
                $em->flush();

                // Sand reset email.
                $sendStatus = $mailer->sendMessage(
                    [$user->getEmail() => $user->getFirstName() . ' ' . $user->getLastName()],
                    'Informatics.Ge Password reset',
                    'email/reset-password.' . $_locale . '.html.twig',
                    [
                        'name' => $user->getFirstName(),
                        'surname' => $user->getLastName(),
                        'token' => $token->getToken(),
                        'expiration' => $token->getExpiration()->format('d.m.Y H:i:s')
                    ]
                );

                // Add flush message
                if ($sendStatus) {
                    $this->addFlash('success', 'flush.reset_email_sent');
                }
            } else {
                $this->addFlash('danger', 'flush.reset_user_not_found');
            }
        }

        return $this->render('default/reset-password.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    /**
     * Reset user password and save in database.
     *
     * @param string $_locale
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param $token
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Exception
     */
    public function reset(string $_locale, Request $request, UserPasswordEncoderInterface $passwordEncoder, $token)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();

        $userRepo = $doctrine->getRepository(User::class);
        $tokenRepo = $doctrine->getRepository(Token::class);

        $tokenObj = $tokenRepo->findOneBy(['token' => $token]);

        if ('reset' !== substr($token, 0, 5)
            || !$tokenObj || (new \DateTime()) > $tokenObj->getExpiration()
        ) {
            $redirection = $this->redirectToRoute('index');

            return $redirection;
        }

        $form = $this->createForm(PasswordResetFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get user.
            $user = $userRepo->findOneBy(['id' => $tokenObj->getUser()]);

            // Update user password.

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $em->persist($user);
            $em->flush();

            // Remove token form db.
            $em->remove($tokenObj);
            $em->flush();

            // Add flush message.
            $this->addFlash( 'success', 'flush.success_reset');

            // Redirect to homepage.

            $redirection = $this->redirectToRoute('index');

            return $redirection;
        }

        return $this->render('default/password-reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }
}
