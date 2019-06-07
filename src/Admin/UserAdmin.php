<?php

namespace App\Admin;

use App\Entity\Role;
use App\Entity\User;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

final class UserAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $container = $this->getConfigurationPool()
            ->getContainer();
        $doctrine = $container->get('doctrine');

        $request = $container->get('request_stack');
        $locale = $request->getCurrentRequest()
            ->getLocale();

        // Get roles.

        $rolesRepo = $doctrine->getRepository(Role::class);

        $roles = $rolesRepo->findAll();

        $roleChoices = [];
        foreach ($roles as $role) {
            /** @var Role $role */
            $roleChoices[$role->getTitle($locale)] = $role->getMachineName();
        }

        $formMapper
            ->with('Account', ['class' => 'col-md-6'])
            ->add('username', TextType::class, [
                'label' => 'username',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9_.]+$/i',
                        'message' => 'validation.username_invalid_chars'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ]),
                    new Email([
                        'message' => 'validation.invalid_email'
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'validation.passwords_not_match',
                'first_options' => ['label' => 'password'],
                'second_options' => ['label' => 'repeat_password'],
                'constraints' => [
                    new Length([
                        'min' => 6,
                        'minMessage' => 'validation.password_length',
                        'max' => 4096,
                    ]),
                ],
                'required' => false
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $roleChoices,
                'expanded' => false,
                'multiple' => true,
                'required' => false,
            ])
            ->add('active', ChoiceType::class, [
                'choices' => [
                    'yes' => true,
                    'no' => false
                ],
            ])
            ->add('verified', ChoiceType::class, [
                'choices' => [
                    'yes' => true,
                    'no' => false
                ],
            ])
            ->end()
            ->with('Personal', ['class' => 'col-md-6'])
            ->add('firstName', TextType::class, [
                'label' => 'first_name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ])
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'last_name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ])
                ]
            ])
            ->add('university', TextType::class, [
                'required' => false
            ])
            ->add('favoriteLanguage', TextType::class, [
                'required' => false
            ])
            ->add('interests', TextType::class, [
                'required' => false
            ])
            ->add('about', TextareaType::class, [
                'required' => false
            ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('registrationDate')
            ->add('active')
            ->add('verified');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->addIdentifier('username')
            ->add('active')
            ->add('verified')
            ->add('email')
            ->add('firstName')
            ->add('lastName')
            ->add('registrationDate');
    }

    public function getExportFields()
    {
        return ['id', 'username', 'email', 'firstName', 'lastName'];
    }

    public function prePersist($object)
    {
        /** @var User $object */

        $container = $this->getConfigurationPool()
            ->getContainer();

        // Get password encoder
        $encoder = $container->get('security.password_encoder');

        // Encode password
        $object->setPassword($encoder->encodePassword(
            $object,
            $object->getPlainPassword()
        ));

        parent::prePersist($object);
    }

    public function preUpdate($object)
    {
        /** @var User $object */

        $container = $this->getConfigurationPool()
            ->getContainer();

        $doctrine = $container->get('doctrine');

        $userRepo = $doctrine->getRepository(User::class);

        // Get editing user from database.
        $user = $userRepo->findOneBy(['id' => $object->getId()]);

        if ($newPassword = $object->getPlainPassword()) {
            // Get password encoder
            $encoder = $container->get('security.password_encoder');

            // Encode password
            $object->setPassword($encoder->encodePassword(
                $object,
                $newPassword
            ));
        } else {
            $object->setPassword($user->getPassword());
        }

    }

}