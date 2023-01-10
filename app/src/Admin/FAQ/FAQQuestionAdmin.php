<?php

namespace App\Admin\FAQ;

use App\Entity\Category;
use App\Entity\FAQ\FAQCategory;
use App\Entity\FAQ\FAQQuestion;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Admin\FAQ\SortableAdminTrait;

class FAQQuestionAdmin extends AbstractAdmin
{
    use SortableAdminTrait;

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('question', TextType::class)
            ->add('answer', CKEditorType::class, [
                'config' => [
                    'uiColor' => '#ffffff',
                ]
            ])
            ->add('category', ModelType::class, [
                'class' => FAQCategory::class,
                'property' => 'name',
            ])
            ->add('active', CheckboxType::class, [
                'required' => false
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('question');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('question')
            ->add('active')
            ->add('category.name')
            ->add(ListMapper::NAME_ACTIONS, ListMapper::TYPE_ACTIONS, [
                'actions' => [
                    'move' => [
                        'template' => '@RunroomSortableBehavior/sort_drag_drop.html.twig',
                        'enable_top_bottom_buttons' => false, // optional
                    ],
                    'edit' => [
                        // You may add custom link parameters used to generate the action url
                        'link_parameters' => [
                            'full' => true,
                        ]
                    ],
                    'delete' => [],
                ]
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('question');
    }

    public function toString(object $object): string
    {
        return $object instanceof FAQQuestion
            ? $object->getQuestion()
            : 'FAQQuestion';
    }
}