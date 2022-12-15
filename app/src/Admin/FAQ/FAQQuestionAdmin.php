<?php

namespace App\Admin\FAQ;

use App\Entity\Category;
use App\Entity\FAQ\FAQCategory;
use App\Entity\FAQ\FAQQuestion;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FAQQuestionAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('question', TextType::class)
            ->add('answer', TextareaType::class)
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
        $list->addIdentifier('question');
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