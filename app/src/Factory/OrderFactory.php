<?php

namespace App\Factory;


use App\Entity\Basket;
use App\Entity\Order;
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
        $basket
            ->setStatus('new');

        return $basket;
    }

    /**
     * Creates an order.
     *
     * @return Order
     */
    public function create(): Order
    {
        $order = new Order();
        $order
            ->setStatus(Order::STATUS_CART)
            ->setOrderDate(new \DateTime());

        return $order;
    }

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