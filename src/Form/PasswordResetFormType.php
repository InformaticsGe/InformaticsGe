<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordResetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'validation.passwords_not_match',
                'options' => ['attr' => ['class' => 'form-control']],
                'first_options' => ['label' => 'new_password'],
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
            ]);
    }

}
