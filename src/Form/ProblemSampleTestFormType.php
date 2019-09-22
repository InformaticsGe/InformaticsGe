<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProblemSampleTestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('input', TextareaType::class, [
                'label' => 'admin.label.input',
                'required' => false,
            ])
            ->add('output', TextareaType::class, [
                'label' => 'admin.label.output',
                'required' => false,
            ]);
    }
}
