<?php

namespace App\Admin;

use App\Entity\Blog;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


final class BlogAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_sort_order' => 'DESC',
        '_sort_by' => 'id',
    ];

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('admin.group.content', [
                'class' => 'col-md-8',
            ])
            ->add('id', NumberType::class, [
                'label' => 'admin.label.id',
            ])
            ->add('title', TextType::class, [
                'label' => 'admin.label.title',
            ])
            ->add('tags', TextType::class, [
                'label' => 'admin.label.tags',
                'required' => false,
            ])
            ->add('body', TextareaType::class, [
                'label' => 'admin.label.body',
            ])
            ->end()
            ->with('admin.group.publication', [
                'class' => 'col-md-4',
                'box_class' => 'box box-warning',
            ])
            ->add('author', '', [
                'label' => 'admin.label.author',
                'associated_property' => 'username'
            ])
            ->add('published', '', [
                'label' => 'admin.label.published',
            ])
            ->add('createdOn', '', [
                'label' => 'admin.label.published_on',
            ])
            ->add('updatedOn', '', [
                'label' => 'admin.label.updated_on',
            ])
            ->end();
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('admin.group.content', [
                'class' => 'col-md-9',
            ])
            ->add('title', TextType::class, [
                'label' => 'admin.label.title',
            ])
            ->add('tags', TextType::class, [
                'label' => 'admin.label.tags',
                'required' => false,
            ])
            ->add('body', CKEditorType::class, [
                'label' => 'admin.label.body',
            ])
            ->end()
            ->with('admin.group.publication', [
                'class' => 'col-md-3',
                'box_class' => 'box box-warning',
            ])
            ->add('author', EntityType::class, [
                'label' => 'admin.label.author',
                'class' => User::class,
                'choice_label' => 'username',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles like :role1')
                        ->orWhere('u.roles like :role2')
                        ->setParameter(':role1', '%ROLE_ADMIN%')
                        ->setParameter(':role2', '%ROLE_SUPER_ADMIN%');
                },
            ])
            ->add('published', ChoiceType::class, [
                'label' => 'admin.label.published',
                'choices' => [
                    'yes' => true,
                    'no' => false
                ],
            ])
            ->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, [
                'label' => 'admin.label.id'
            ])
            ->add('title', null, [
                'label' => 'admin.label.title'
            ])
            ->add('tags', null, [
                'label' => 'admin.label.tags'
            ])
            ->add('body', null, [
                'label' => 'admin.label.body'
            ])
            ->add('createdOn', DateTimeFilter::class, [
                'label' => 'admin.label.published_on'
            ])
            ->add('updatedOn', DateTimeFilter::class, [
                'label' => 'admin.label.updated_on'
            ])
            ->add('published', null, [
                'label' => 'admin.label.published'
            ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', NumberType::class, [
                'label' => 'admin.label.id'
            ])
            ->addIdentifier('title', NumberType::class, [
                'label' => 'admin.label.title',
                'route' => ['name' => 'show']
            ])
            ->add('published', '', [
                'label' => 'admin.label.published'
            ])
            ->add('author', '', [
                'label' => 'admin.label.author',
                'associated_property' => 'username'
            ])
            ->add('createdOn', '', [
                'label' => 'admin.label.published_on'
            ])
            ->add('updatedOn', '', [
                'label' => 'admin.label.updated_on'
            ]);
    }

    public function preUpdate($object)
    {
        /** @var Blog $object */

        // Update blog updating date and time.
        $object->setUpdatedOn(new \DateTime());

        parent::preUpdate($object);
    }
}