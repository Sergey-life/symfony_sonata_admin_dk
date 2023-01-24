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
use Symfony\Component\Validator\Constraints\File;

class PostAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('Content', [
                    'class' => 'col-md-9',
                    'label' => 'Контент'
                ])
                ->add('title', TextType::class)
                ->add('body', TextareaType::class)
            ->end()
            ->with('Meta data', [
                    'class' => 'col-md-3',
                    'label' => 'Мета дані'
                ])
                ->add('active', CheckboxType::class, [
                    'required' => false,
                ])
                ->add('published', CheckboxType::class, [
                    'required' => false,
                ])
                ->add('category', ModelType::class, [
                    'class' => Category::class,
                    'property' => 'name',
            ])
                ->add('file', FileType::class, [
                    'required' => false,
                    'label' => 'Зображення',
                    'constraints' => [
                        new File([
                            'maxSize' => '2048k',
                            'mimeTypes' => [
                                'image/*',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image',
                        ])
                    ]
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
                'label' => 'Категорія',
                'field_options' => [
                    'class' => Category::class,
                    'choice_label' => 'name',
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
            ->add('image', null , ['template' => 'Admin/image_post.html.twig'])
        ->add(ListMapper::NAME_ACTIONS, ListMapper::TYPE_ACTIONS, [
        'actions' => [
            'edit' => [
                // You may add custom link parameters used to generate the action url
                'link_parameters' => [
                    'full' => true,
                ]
            ],
            'delete' => [],
            'send email' => ['template' => 'email/button_send_email_post.html.twig'],
        ]
    ]);
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