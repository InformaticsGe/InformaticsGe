<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return 'sign_in' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $loginFormData = $request->request->get('login_form');

        $credentials = [
            'username' => isset($loginFormData['username']) ? $loginFormData['username'] : '',
            'password' => isset($loginFormData['password']) ? $loginFormData['password'] : '',
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if ($this->csrfTokenManager->isTokenValid($token)) {
            $userRepo = $this->entityManager->getRepository(User::class);
            $user = $userRepo->findOneBy(['username' => $credentials['username']]) ?:
                $userRepo->findOneBy(['email' => $credentials['username']]);

            // If User not found or passwords don't match.
            if (!$user || !$this->checkCredentials($credentials, $user)) {
                throw new CustomUserMessageAuthenticationException('sign_in.invalid_credentials');
            }

            // If account not verified.
            if (!$user->isVerified()) {
                throw new CustomUserMessageAuthenticationException('sign_in.user_not_verified');
            }

            // If account is inactive.
            if (!$user->isActive()) {
                throw new CustomUserMessageAuthenticationException('sign_in.user_is_inactive');
            }

            return $user;
        }

        throw new CustomUserMessageAuthenticationException('invalid_token');
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // TODO: Redirect to user profile
        return new RedirectResponse('/');
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('sign_in');
    }
}
