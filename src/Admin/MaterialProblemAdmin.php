<?php

declare(strict_types=1);

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class MaterialProblemAdmin extends AbstractAdmin
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
            ->add('url')
            ->add('urlTitle')
            ->add('problem')
            ->add('analyse');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->addIdentifier('id', NumberType::class, [
                'label' => 'admin.label.id'
            ])
            ->addIdentifier('title', NumberType::class, [
                'label' => 'admin.label.title'
            ])
            ->add('tags', TextType::class, [
                'label' => 'admin.label.tags'
            ])
            ->add('url', UrlType::class, [
                'label' => 'admin.label.url'
            ])
            ->add('_action', null, [
                'actions' => [
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('title', TextType::class, [
                'label' => 'admin.label.title',
            ])
            ->add('tags', TextType::class, [
                'label' => 'admin.label.tags',
                'required' => false,
            ])
            ->add('url', UrlType::class, [
                'label' => 'admin.label.url',
                'required' => false,
            ])
            ->add('urlTitle', TextType::class, [
                'label' => 'admin.label.url_title',
                'data' => $this->trans('this_problem_on_codeforces', [], 'messages'),
                'required' => false,
            ])
            ->add('problem', CKEditorType::class, [
                'label' => 'admin.label.problem',
            ])
            ->add('analyse', CKEditorType::class, [
                'label' => 'admin.label.analyse',
            ]);
    }
}
