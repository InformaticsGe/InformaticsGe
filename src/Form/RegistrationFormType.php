<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'username',
                'attr' => ['class' => 'form-control'],
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
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ]),
                    new Email([
                        'message' => 'validation.invalid_email'
                    ])
                ]
            ])
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
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'validation.passwords_not_match',
                'options' => ['attr' => ['class' => 'form-control']],
                'first_options' => ['label' => 'password'],
                'second_options' => ['label' => 'repeat_password'],
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
            ])
            ->add('terms', CheckboxType::class, [
                'label' => 'agree_with_terms',
                'attr' => ['class' => 'form-check-input'],
                'label_attr' => ['class' => 'form-check-label'],
                'mapped' => false,
                'constraints' => new IsTrue([
                    'message' => 'validation.agree_with_terms'
                ]),
            ])
            ->add('recaptcha', TextType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['hidden' => 'hidden'],
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
