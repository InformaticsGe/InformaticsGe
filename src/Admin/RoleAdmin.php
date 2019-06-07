<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class RoleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('machineName', TextType::class, [
                'label' => 'admin.label.machine_name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ])
                ]
            ])
            ->add('titleKA', TextType::class, [
                'label' => 'admin.label.title_ka',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ])
                ]
            ])
            ->add('titleEN', TextType::class, [
                'label' => 'admin.label.title_en',
                'constraints' => [
                    new NotBlank([
                        'message' => 'validation.is_blank'
                    ])
                ]
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, [
                'label' => 'admin.label.id'
            ])
            ->add('machineName', null, [
                'label' => 'admin.label.machine_name'
            ])
            ->add('titleKA', null, [
                'label' => 'admin.label.title_ka'
            ])
            ->add('titleEN', null, [
                'label' => 'admin.label.title_en'
            ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', NumberType::class, [
                'label' => 'admin.label.id'
            ])
            ->addIdentifier('machineName', TextType::class, [
                'label' => 'admin.label.machine_name'
            ])
            ->add('titleKA', TextType::class, [
                'label' => 'admin.label.title_ka'
            ])
            ->add('titleEN', TextType::class, [
                'label' => 'admin.label.title_en'
            ]);
    }

}