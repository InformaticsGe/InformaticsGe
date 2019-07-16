<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    public function profile($_locale, $username, UserRepository $repository, TranslatorInterface $translator)
    {
        $user = $repository->findOneBy(['username' => $username]);

        if (null === $user || !$user->isActive() || !$user->isVerified()) {
            throw new NotFoundHttpException($translator->trans('user_not_found', ['%username%' => $username]));
        }

        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
