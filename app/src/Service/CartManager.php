<?php

namespace App\Service;

use App\Entity\Basket;
use App\Entity\BasketItem;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Factory\OrderFactory;
use App\Repository\BasketItemRepository;
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

    private $basketItemRepository;
    /**
     * CartManager constructor.
     *
     * @param CartSessionStorage $cartStorage
     * @param OrderFactory $orderFactory
     * @param EntityManagerInterface $entityManager
     * @param BasketItemRepository $basketItemRepository
     */
    public function __construct(
        CartSessionStorage $cartStorage,
        OrderFactory $orderFactory,
        EntityManagerInterface $entityManager,
        BasketItemRepository $basketItemRepository
    ) {
        $this->cartSessionStorage = $cartStorage;
        $this->cartFactory = $orderFactory;
        $this->entityManager = $entityManager;
        $this->basketItemRepository = $basketItemRepository;
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
            $orderItem = new OrderItem();
            $orderItem
                ->setTotal($item->getTotal())
                ->setPrice($item->getPrice())
                ->setQuantity($item->getQuantity())
                ->setProduct($item->getProduct())
                ->setOrderRef($newOrder);

            $this->save($orderItem);
        }

        $basket->setStatus(Basket::STATUS_BASKET['closed']);
        $this->save($basket);
    }

    public function addItem($prodId, $quantity, $basket)
    {
        $basketItem = $this->basketItemRepository->findOneBy([
            'product' => $prodId,
            'basket' => $basket->getId()
        ]);
//        dd($basketItem->getQuantity() + $quantity, $basketItem->getPrice()*($basketItem->getQuantity() + $quantity));
        $basketItem
            ->setQuantity($basketItem->getQuantity() + $quantity)
            ->setTotal($basketItem->getPrice() * $basketItem->getQuantity());

        $this->save($basketItem);
//        foreach ($this->getCurrentBasket()->getItems() as $existingItem) {
////             The item already exists, update the quantity
//            if ($existingItem->equals($item) && $resetQuantity) {
//                $existingItem->setQuantity(
//                    $existingItem->getQuantity() + $item->getQuantity()
//                )
//                    ->setPrice($item->getProduct()->getPrice())
//                    ->setTotal($item->getProduct()->getPrice() * $existingItem->getQuantity())
//                    ->setProduct($item->getProduct());
//
//                return $this;
//            }
//        }
//
//        $this->items[] = $item;
//        $item->setTotal($item->getTotalPrice())
//            ->setBasket($this->getCurrentBasket())
//            ->setProduct($item->getProduct())
//            ->setPrice($item->getProduct()->getPrice());
//
//        return $this;
    }
}