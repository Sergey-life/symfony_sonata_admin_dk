<?php

namespace App\Factory;


use App\Entity\Basket;
use App\Entity\Order;
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
//     * @return Order
//     */
//    public function createOrder($orderItems): Order
//    {
//        foreach ($orderItems as $item) {
//            $order = new Order();
//            $order
//                ->setTotal($item->getTotal())
//                ->setPrice($item->getPrice())
//                ->setQuantity($item->getQuantity())
//                ->setProduct($item->getProduct())
//                ->setOrderRef($item->getId());
//        }
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