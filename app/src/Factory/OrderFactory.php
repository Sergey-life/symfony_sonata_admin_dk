<?php

namespace App\Factory;


use App\Entity\Basket;
use App\Entity\OrderItem;
use App\Entity\BasketItem;
use App\Entity\Product;

/**
 * Class OrderFactory
 * @package App\Factory
 */

class OrderFactory
{
    /**
     * Creates an order.
     *
     * @return Basket
     */
    public function createBasket(): Basket
    {
        $basket = new Basket();
        $basket->setStatus(Basket::STATUS_BASKET['open']);

        return $basket;
    }

//    /**
//     * Creates an order.
//     *
//     * @return OrderItem
//     */
//    public function create(): OrderItem
//    {
//        $order = new OrderItem();
//        $order
//            ->setStatus(OrderItem::STATUS_CART)
//            ->setOrderDate(new \DateTime());
//
//        return $order;
//    }

    /**
     * Creates an item for a product.
     *
     * @param Product $product
     *
     * @return BasketItem
     */
    public function createItem(Product $product): BasketItem
    {
        $item = new BasketItem();
        $item->setProduct($product);
        $item->setQuantity(1);

        return $item;
    }
}