<?php

namespace App\Admin;

use App\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('title', TextType::class)
            ->add('body', TextareaType::class)
            ->add('active', CheckboxType::class)
            ->add('published', CheckboxType::class)
            ->add('category', ModelType::class, [
                'class' => Category::class,
                'property' => 'name'
        ])
            ->add('image', FileType::class, [
                'required' => false,
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('title');
        $filter->add('body');
        $filter->add('active');
        $filter->add('published');
        $filter->add('category');
        $filter->add('image');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('title');
        $list->addIdentifier('body');
        $list->addIdentifier('active');
        $list->addIdentifier('published');
        $list->addIdentifier('category');
        $list->addIdentifier('image');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('title');
        $show->add('body');
        $show->add('active');
        $show->add('published');
        $show->add('category');
        $show->add('image');
    }

    public function prePersist(object $image): void
    {
        $this->manageFileUpload($image);
    }

    public function preUpdate(object $image): void
    {
        $this->manageFileUpload($image);
    }

    private function manageFileUpload(object $image): void
    {
        if ($image->getFile()) {
            $image->refreshUpdated();
        }
    }
}