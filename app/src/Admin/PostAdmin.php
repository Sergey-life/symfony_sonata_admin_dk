<?php

namespace App\Admin;

use App\Entity\Category;
use App\Entity\Post;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Content', [
                    'class' => 'col-md-9',
                    'label' => 'Контент'
                ])
                ->add('title', TextType::class, ['label' => 'Заголовок'], ['translation_domain' => 'AnotherDomain'])
                ->add('body', TextareaType::class, ['label' => 'Текст поста'])
            ->end()
            ->with('Meta data', [
                    'class' => 'col-md-3',
                    'label' => 'Мета дані'
                ])
                ->add('active', CheckboxType::class, [
                    'data' => false,
                    'required' => false,
                    'label' => 'Активний'
                ])
                ->add('published', CheckboxType::class, [
                    'data' => false,
                    'required' => false,
                    'label' => 'Опублікований'
                ])
                ->add('category', ModelType::class, [
                    'class' => Category::class,
                    'property' => 'name',
                    'label' => 'Категорія'
            ])
                ->add('file', FileType::class, [
                    'required' => false,
                    'label' => 'Файл'
                ])
        ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('title')
            ->add('body')
            ->add('active')
            ->add('published')
            ->add('category', null, [
                'field_type' => EntityType::class,
                'field_options' => [
                    'class' => Category::class,
                    'choice_label' => 'name'
                ]
            ])
            ->add('image');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('title')
            ->add('body')
            ->add('active')
            ->add('published')
            ->add('category.name')
            ->add('image');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('title')
            ->add('body')
            ->add('active')
            ->add('published')
            ->add('category.name')
            ->add('image');
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
            $image->lifecycleFileUpload();
        }
    }

    public function toString(object $object): string
    {
        return $object instanceof Post
            ? $object->getTitle()
            : 'Post';
    }
}