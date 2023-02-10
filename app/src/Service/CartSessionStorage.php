<?php

namespace App\Service;

use App\Entity\Basket;
use App\Entity\Order;
use App\Repository\BasketRepository;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartSessionStorage
{
    /**
     * The request stack.
     *
     * @var RequestStack
     */
    private $requestStack;

    /**
     * The cart repository.
     *
     * @var OrderRepository
     */
    private $cartRepository;

    /**
     * The cart repository.
     *
     * @var BasketRepository
     */
    private $basketRepository;

    /**
     * @var string
     */
    const CART_KEY_NAME = 'id';

    /**
     * CartSessionStorage constructor.
     *
     * @param RequestStack $requestStack
     * @param OrderRepository $cartRepository
     * @param BasketRepository $asketRepository
     */
    public function __construct(RequestStack $requestStack, OrderRepository $cartRepository, BasketRepository $basketRepository)
    {
        $this->requestStack = $requestStack;
        $this->cartRepository = $cartRepository;
        $this->basketRepository = $basketRepository;
    }

    /**
     * Gets the cart in session.
     *
     * @return Order|null
     */
    public function getCart(): ?Basket
    {
        return $this->cartRepository->findOneBy([
            'id' => 1,//$this->getCartId(),
            'status' => Order::STATUS_CART
        ]);
    }

    //test method
    public function getBasket(): ?Basket
    {
        return $this->basketRepository->findOneBy([
            'id' => 1,//$this->getCartId(),
            'status' => 'new'
        ]);
    }

    /**
     * Sets the cart in session.
     *
     * @param Basket $cart
     */
    public function setCart(Basket $cart): void
    {
        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    /**
     * Returns the cart id.
     *
     * @return int|null
     */
    private function getCartId(): ?int
    {
        return $this->getSession()->get(self::CART_KEY_NAME);
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }
}