<?php

namespace App\Admin\FAQ;

use App\Entity\FAQ\FAQCategory;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Admin\FAQ\SortableAdminTrait;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;


class FAQCategoryAdmin extends AbstractAdmin
{
    use SortableAdminTrait;

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name', TextType::class)
            ->add('active', CheckboxType::class, [
                'required' => false
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('name');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('name')
            ->add('active')
            ->add('position')
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
                    'questions and answers' => ['template' => 'Admin/question_and_answer.html.twig'],
                ]
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name');
    }

    public function toString(object $object): string
    {
        return $object instanceof FAQCategory
            ? $object->getName()
            : 'FAQCategory';
    }

    protected function configureTabMenu(MenuItemInterface $menu, string $action, ?AdminInterface $childAdmin = null): void
    {
        if (!$childAdmin && !in_array($action, ['edit', 'show'])) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

//        $menu->addChild('View FAQCategory', $admin->generateMenuUrl('show', ['id' => $id]));

        if ($this->isGranted('EDIT')) {
            $menu->addChild('Edit', $admin->generateMenuUrl('edit', ['id' => $id]));
        }

        if ($this->isGranted('LIST')) {
            $menu->addChild('Questions and answers', $admin->generateMenuUrl('admin.faq-question.list', ['id' => $id]));
        }
    }
}