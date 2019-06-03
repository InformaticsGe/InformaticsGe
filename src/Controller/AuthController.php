<?php

namespace App\Controller;

use App\Entity\Token;
use App\Entity\User;
use App\Form\LoginFormType;
use App\Form\RegistrationFormType;
use App\Service\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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
     * @param Mailer $mailer
     *
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function register(string $_locale, Request $request, UserPasswordEncoderInterface $passwordEncoder, Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

            $redirection = $this->redirectToRoute('index', ['status_code' => 'success_registration']);

            return $redirection;
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
    public function login(string $_locale,AuthenticationUtils $authenticationUtils): Response
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function verify(string $_locale, $token)
    {
        $doctrine = $this->getDoctrine();
        $em = $doctrine->getManager();
        $tokenRepo = $doctrine->getRepository(Token::class);
        $tokenObj = $tokenRepo->findOneBy(['token' => $token]);

        $redirectOptions = [];
        if ($tokenObj && (new \DateTime()) <= $tokenObj->getExpiration()) {
            $userRepo = $doctrine->getRepository(User::class);
            $user = $userRepo->find($tokenObj->getUser());

            // Verify user.
            $user->setVerified(true);
            $em->persist($user);
            $em->flush();

            // Remove token from db.
            $em->remove($tokenObj);
            $em->flush();

            $redirectOptions = [
                'status_code' => 'success_verification'
            ];
        }

        $redirection = $this->redirectToRoute('index', $redirectOptions);

        return $redirection;
    }
}
