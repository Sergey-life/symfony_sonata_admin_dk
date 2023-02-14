<?php

namespace App\Service;

use App\Entity\Basket;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Factory\OrderFactory;
use App\Service\CartSessionStorage;
use Doctrine\ORM\EntityManagerInterface;

class CartManager
{
    /**
     * @var CartSessionStorage
     */
    private $cartSessionStorage;

    /**
     * @var OrderFactory
     */
    private $cartFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * CartManager constructor.
     *
     * @param CartSessionStorage $cartStorage
     * @param OrderFactory $orderFactory
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        CartSessionStorage $cartStorage,
        OrderFactory $orderFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * Gets the current cart.
     *
     * @return Basket
     */
    public function getCurrentBasket(): Basket
    {
        $basket = $this->cartSessionStorage->getBasket();
        if (!$basket) {
            $basket = $this->cartFactory->createBasket();
        }

        return $basket;
    }

    /**
     * Persists the cart in database and session.
     *
     * @param mixed $basket
     */
    public function save($basket): void
    {
        // Persist in database
        $this->entityManager->persist($basket);
        $this->entityManager->flush();
        // Persist in session
        if ($basket instanceof Basket) {
            $this->cartSessionStorage->setCart($basket);
        }
    }

    /**
     * Persists the cart in database and session.
     *
     * @param mixed $basket
     */
    public function delete($basket)
    {
        $this->entityManager->remove($basket);
    }

    /**
     * Creates an order.
     *
     * @param Basket
     */
    public function createOrder($basket)
    {
        $newOrder = new Order();
        $newOrder
            ->setStatus(Order::STATUS_ORDER['new'])
            ->setTotalSum($basket->getTotal());
        $this->save($newOrder);

        foreach ($basket->getItems() as $item) {
            $order = new OrderItem();
            $order
                ->setTotal($item->getTotal())
                ->setPrice($item->getPrice())
                ->setQuantity($item->getQuantity())
                ->setProduct($item->getProduct())
                ->setOrderRef($newOrder);

            $this->save($order);
        }

        $basket->setStatus(Basket::STATUS_BASKET['closed']);
        $this->save($basket);
    }
}