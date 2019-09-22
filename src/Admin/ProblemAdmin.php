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
            ->add('id', null, [
                'label' => 'admin.label.id',
            ])
            ->add('title', null, [
                'label' => 'admin.label.title',
            ])
            ->add('tags', null, [
                'label' => 'admin.label.tags',
            ])
            ->add('text', null, [
                'label' => 'admin.label.text',
            ])
            ->add('notes', null, [
                'label' => 'admin.label.notes',
            ])
            ->add('timeLimit', null, [
                'label' => 'admin.label.time_limit',
            ])
            ->add('memoryLimit', null, [
                'label' => 'admin.label.memory_limit',
            ])
            ->add('inputType', null, [
                'label' => 'admin.label.input_type',
            ])
            ->add('outputType', null, [
                'label' => 'admin.label.output_type',
            ])
            ->add('sourceTitle', null, [
                'label' => 'admin.label.source_title',
            ])
            ->add('sourceUrl', null, [
                'label' => 'admin.label.source_url',
            ])
            ->add('visible', null, [
                'label' => 'admin.label.visible',
            ]);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('id', null, [
                'label' => 'admin.label.id',
            ])
            ->add('title', null, [
                'label' => 'admin.label.title',
            ])
            ->add('tags', null, [
                'label' => 'admin.label.tags',
            ])
            ->add('sourceTitle', null, [
                'label' => 'admin.label.source_title',
            ])
            ->add('visible', null, [
                'label' => 'admin.label.visible',
            ])
            ->add('_action', null, [
                'label' => 'admin.label.actions',
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
            ->add('id', null, [
                'label' => 'admin.label.id',
            ])
            ->add('title', null, [
                'label' => 'admin.label.title',
            ])
            ->add('tags', null, [
                'label' => 'admin.label.tags',
            ])
            ->add('text', null, [
                'label' => 'admin.label.text',
            ])
            ->add('notes', null, [
                'label' => 'admin.label.notes',
            ])
            ->add('timeLimit', null, [
                'label' => 'admin.label.time_limit',
            ])
            ->add('memoryLimit', null, [
                'label' => 'admin.label.memory_limit',
            ])
            ->add('inputType', null, [
                'label' => 'admin.label.input_type',
            ])
            ->add('outputType', null, [
                'label' => 'admin.label.output_type',
            ])
            ->add('sourceTitle', null, [
                'label' => 'admin.label.source_title',
            ])
            ->add('sourceUrl', null, [
                'label' => 'admin.label.source_url',
            ])
            ->add('visible', null, [
                'label' => 'admin.label.visible',
            ]);
    }
}
