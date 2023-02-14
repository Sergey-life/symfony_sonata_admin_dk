<?php

namespace App\Service;

use App\Entity\Basket;
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
        $this->cartSessionStorage->setCart($basket);
    }

    public function saveBasket(Basket $basket): void
    {
        // Persist in database
        $this->entityManager->persist($basket);
        $this->entityManager->flush();
        // Persist in session
//        $this->cartSessionStorage->setCart($basket);
    }
}