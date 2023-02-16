<?php

namespace App\Service;

use App\Entity\Basket;
use App\Entity\OrderItem;
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
     * @param BasketRepository $asketRepository
     */
    public function __construct(RequestStack $requestStack, BasketRepository $basketRepository)
    {
        $this->requestStack = $requestStack;
        $this->basketRepository = $basketRepository;
    }

    /**
     * Gets the cart in session.
     *
     * @return Basket|null
     */

    public function getBasket(): ?Basket
    {
        return $this->basketRepository->findOneBy([
            'id' => $this->getCartId() ? 20 : $this->getCartId(),
            'status' => Basket::STATUS_BASKET['open']
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