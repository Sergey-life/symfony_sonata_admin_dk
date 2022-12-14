<?php

namespace App\Admin;

use App\Entity\Category;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CategoryAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('name', TextType::class, ['label' => 'Назва']);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter->add('name', null, ['label' => 'Назва']);
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('name', null, ['label' => 'Назва']);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name', null, ['label' => 'Назва']);
    }

    public function toString(object $object): string
    {
        return $object instanceof Category
            ? $object->getName()
            : 'Category';
    }
}