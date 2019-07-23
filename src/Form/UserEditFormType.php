<?php

namespace App\Form;

use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('avatar', HiddenType::class)
            ->add('firstName', TextType::class, [
                'label' => 'first_name',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ])
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'last_name',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ])
                ]
            ])
            ->add('university', TextType::class, [
                'label' => 'school',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => 'city',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('favoriteLanguage', TextType::class, [
                'label' => 'favorite_language',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('about', CKEditorType::class, [
                'label' => 'about_me',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->add('interests', TextType::class, [
                'label' => 'interests',
                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('oldPassword', PasswordType::class, [
                'label' => 'old_password',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'required' => false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'validation.passwords_not_match',
                'options' => ['attr' => ['class' => 'form-control']],
                'first_options' => ['label' => 'new_password'],
                'second_options' => ['label' => 'repeat_password'],
                'required' => false
            ]);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $formData = $event->getData();
            $form = $event->getForm();

            if (isset($formData['oldPassword']) && '' != $formData['oldPassword']) {
                $form->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'validation.passwords_not_match',
                    'options' => ['attr' => ['class' => 'form-control']],
                    'first_options' => ['label' => 'new_password'],
                    'second_options' => ['label' => 'repeat_password'],
                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'validation.is_blank',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'validation.password_length',
                            'max' => 4096,
                        ]),
                    ]
                ]);
            } else {
                $form->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'validation.passwords_not_match',
                    'options' => ['attr' => ['class' => 'form-control']],
                    'first_options' => ['label' => 'new_password'],
                    'second_options' => ['label' => 'repeat_password'],
                    'required' => false
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
