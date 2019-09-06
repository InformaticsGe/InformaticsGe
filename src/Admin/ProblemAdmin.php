<?php

declare(strict_types=1);

namespace App\Admin;

use App\Form\ProblemSampleTestFormType;
use App\Form\ProblemTestFormType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\CollectionType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class ProblemAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'id',
    ];

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('id')
            ->add('title')
            ->add('tags')
            ->add('text')
            ->add('notes')
            ->add('timeLimit')
            ->add('memoryLimit')
            ->add('inputType')
            ->add('outputType')
            ->add('sourceTitle')
            ->add('sourceUrl')
            ->add('visible')
            ->add('sampleTests')
            ;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id')
            ->add('title')
            ->add('tags')
            ->add('sourceTitle')
            ->add('visible')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->with('admin.group.main_information', [
                'class' => 'col-md-6',
                'box_class' => 'box box-info',
            ])
            ->add('title', TextType::class, [
                'label' => 'admin.label.title',
            ])
            ->add('tags', TextType::class, [
                'label' => 'admin.label.tags',
                'required' => false,
            ])
            ->add('sourceTitle', TextType::class, [
                'label' => 'admin.label.source_title',
                'required' => false,
            ])
            ->add('sourceUrl', UrlType::class, [
                'label' => 'admin.label.source_url',
                'required' => false,
            ])
            ->add('visible', CheckboxType::class, [
                'label' => 'admin.label.visible',
                'required' => false,
            ])
            ->end()
            ->with('admin.group.limits_and_data_types', [
                'class' => 'col-md-6',
                'box_class' => 'box box-warning',
            ])
            ->add('timeLimit', IntegerType::class, [
                'label' => 'admin.label.time_limit',
            ])
            ->add('memoryLimit', IntegerType::class, [
                'label' => 'admin.label.memory_limit',
            ])
            ->add('inputType', TextType::class, [
                'label' => 'admin.label.input_type',
                'empty_data' => 'stdin',
            ])
            ->add('outputType', TextType::class, [
                'label' => 'admin.label.output_type',
                'empty_data' => 'stdout',
            ])
            ->end()
            ->with('admin.group.content', [
                'class' => 'col-md-12',
                'box_class' => 'box box-success',
            ])
            ->add('text', CKEditorType::class, [
                'label' => 'admin.label.text',
            ])
            ->add('notes', CKEditorType::class, [
                'label' => 'admin.label.notes',
                'required' => false,
            ])
            ->end()
            ->with('admin.group.tests', [
                'class' => 'col-md-12',
                'box_class' => 'box box-danger',
            ])
            ->add('sampleTests', CollectionType::class, [
                'label' => 'admin.label.sample_tests',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'entry_type' => ProblemSampleTestFormType::class,
                'required' => false,
            ])
            ->add('tests', CollectionType::class, [
                'label' => 'admin.label.tests',
                'by_reference' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'entry_type' => ProblemTestFormType::class,
                'required' => true,
            ])
            ->end();
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('id')
            ->add('title')
            ->add('tags')
            ->add('text')
            ->add('notes')
            ->add('timeLimit')
            ->add('memoryLimit')
            ->add('inputType')
            ->add('outputType')
            ->add('sourceTitle')
            ->add('sourceUrl')
            ->add('visible');
    }
}
