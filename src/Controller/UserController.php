<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserController extends AbstractController
{
    /**
     * Show user profile.
     *
     * @param $_locale
     * @param $username
     * @param UserRepository $repository
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Edit user profile.
     *
     * @param Security $security
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param TranslatorInterface $translator
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(
        Security $security,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder,
        TranslatorInterface $translator)
    {
        /** @var User $user */
        $user = $security->getUser();

        $form = $this->createForm(UserEditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $validated = true;

            // Check if user needs to change password.
            if ($oldPassword = $form->get('oldPassword')->getData()) {
                // Check if old password is correct.
                if (!$passwordEncoder->isPasswordValid($user, $oldPassword)) {
                    $form->get('oldPassword')->addError(
                        new FormError($translator->trans('validation.wrong_old_password', [], 'validators'))
                    );

                    $validated = false;
                } else {
                    // Encode plain password
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $user->getPlainPassword()
                        )
                    );
                }
            }

            // Update user entity, if form is valid.
            if ($validated) {
                $em->persist($user);
                $em->flush();

                $this->addFlash( 'success', 'flush.success_profile_update');
            } else {
                $this->addFlash( 'danger', 'flush.validation_error');
            }
        }

        return $this->render('user/edit.html.twig', [
            'editForm' => $form->createView(),
            'user' => $user,
        ]);
    }
}
