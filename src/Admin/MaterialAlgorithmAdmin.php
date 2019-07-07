<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\MaterialAlgorithm;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\Form\Validator\ErrorElement;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class MaterialAlgorithmAdmin extends AbstractAdmin
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
            ->add('filename');
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
            ->add('filename', TextType::class, [
                'label' => 'admin.label.filename'
            ])
            ->add('_action', null, [
                'label' => 'admin.label.actions',
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
            ->add('file', FileType::class, [
                'label' => 'admin.label.file',
                'required' => false
            ]);
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        /** @var MaterialAlgorithm $object */
        if (
            null === $object->getId()
            && null === $object->getFilename()
        ) {
            $errorElement
                ->with('file')
                ->assertNotNull(array())
                ->end();
        }

        parent::validate($errorElement, $object);
    }

    public function preRemove($object)
    {
        /** @var MaterialAlgorithm $object */
        $this->_removeFile($object->getFilename());

        parent::preRemove($object);
    }

    public function getBatchActions()
    {
        $actions = parent::getBatchActions();
        unset($actions['delete']);

        return $actions;
    }

    private function _removeFile(string $fileName): void
    {
        $filePath = __DIR__ . '/../../public/uploads/algorithms' . '/' . $fileName;

        $filesystem = new Filesystem();

        if ($filesystem->exists($filePath)) {
            $filesystem->remove($filePath);
        }
    }

}
