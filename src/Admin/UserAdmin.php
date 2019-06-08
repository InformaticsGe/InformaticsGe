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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
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

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'id',
    ];

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
            ->with('admin.group.account', [
                'class' => 'col-md-6',
                'box_class' => 'box box-danger',
            ])
            ->add('username', TextType::class, [
                'label' => 'admin.label.username',
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
                'label' => 'admin.label.email',
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
                'first_options' => ['label' => 'admin.label.password'],
                'second_options' => ['label' => 'admin.label.repeat_password'],
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
                'label' => 'admin.label.roles',
                'choices' => $roleChoices,
                'expanded' => false,
                'multiple' => true,
                'required' => false,
            ])
            ->add('active', ChoiceType::class, [
                'label' => 'admin.label.active',
                'choices' => [
                    'yes' => true,
                    'no' => false
                ],
            ])
            ->add('verified', ChoiceType::class, [
                'label' => 'admin.label.verified',
                'choices' => [
                    'yes' => true,
                    'no' => false
                ],
            ])
            ->end()
            ->with('admin.group.personal', ['class' => 'col-md-6'])
            ->add('firstName', TextType::class, [
                'label' => 'admin.label.first_name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ])
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'admin.label.last_name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ])
                ]
            ])
            ->add('university', TextType::class, [
                'label' => 'admin.label.university',
                'required' => false
            ])
            ->add('favoriteLanguage', TextType::class, [
                'label' => 'admin.label.favorite_language',
                'required' => false
            ])
            ->add('interests', TextType::class, [
                'label' => 'admin.label.intereset',
                'required' => false
            ])
            ->add('about', TextareaType::class, [
                'label' => 'admin.label.about',
                'required' => false
            ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, [
                'label' => 'admin.label.id'
            ])
            ->add('username', null, [
                'label' => 'admin.label.username'
            ])
            ->add('email', null, [
                'label' => 'admin.label.email'
            ])
            ->add('firstName', null, [
                'label' => 'admin.label.first_name'
            ])
            ->add('lastName', null, [
                'label' => 'admin.label.last_name'
            ])
            ->add('registrationDate', null, [
                'label' => 'admin.label.registration_date'
            ])
            ->add('active', null, [
                'label' => 'admin.label.active'
            ])
            ->add('verified', null, [
                'label' => 'admin.label.verified'
            ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', NumberType::class, [
                'label' => 'admin.label.id'
            ])
            ->addIdentifier('username', TextType::class, [
                'label' => 'admin.label.username'
            ])
            ->add('active', '', [
                'label' => 'admin.label.active'
            ])
            ->add('verified', '', [
                'label' => 'admin.label.verified'
            ])
            ->add('email', EmailType::class, [
                'label' => 'admin.label.email'
            ])
            ->add('firstName', TextType::class, [
                'label' => 'admin.label.first_name'
            ])
            ->add('lastName', TextType::class, [
                'label' => 'admin.label.last_name'
            ])
            ->add('registrationDate', '', [
                'label' => 'admin.label.registration_date'
            ]);
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