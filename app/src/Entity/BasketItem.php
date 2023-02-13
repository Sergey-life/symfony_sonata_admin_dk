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
    #[Assert\NotBlank()]
    #[Assert\GreaterThanOrEqual(1)]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?float $sum = null;

    #[ORM\Column]
    private ?float $totalSum = null;

//    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?Order $orderRef = null;

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

    /**
     * Calculates the item total.
     *
     * @return float|int
     */
    public function getTotal(): float
    {
        return $this->getProduct()->getPrice() * $this->getQuantity();
    }
//test method
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getSum(): ?float
    {
        return $this->sum;
    }

    public function setSum(float $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function getTotalSum(): ?float
    {
        return $this->totalSum;
    }

    public function setTotalSum(float $totalSum): self
    {
        $this->totalSum = $totalSum;

        return $this;
    }
}
