<?php

namespace App\Form;

use App\Entity\BasketItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddToBasketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity')
//            ->add('product')
//            ->add('basket')
//            ->add('orderRef')
            ->add('add', SubmitType::class, [
                'label' => 'Add to cart'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BasketItem::class,
        ]);
    }
}
