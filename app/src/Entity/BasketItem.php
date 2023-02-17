<?php

namespace App\Entity;

use App\Repository\BasketItemRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BasketItemRepository::class)]
class BasketItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'basketItems')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'items', targetEntity: Basket::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Basket $basket = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(1)]
//    #[Assert\PositiveOrZero]
//    #[Assert\Positive]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $total = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getBasket(): ?Basket
    {
        return $this->basket;
    }

    public function setBasket(?Basket $basket): self
    {
        $this->basket = $basket;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Calculates the item total.
     *
     * @return float|int
     */
    public function getTotalPrice(): float
    {
        return $this->getProduct()->getPrice() * $this->getQuantity();
    }

    /**
     * Tests if the given item given corresponds to the same order item.
     *
     * @param BasketItem $item
     *
     * @return bool
     */
    public function equals(BasketItem $item): bool
    {
        return $this->getProduct()->getId() === $item->getProduct()->getId();
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }
}