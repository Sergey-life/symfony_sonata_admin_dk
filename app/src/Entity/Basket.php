<?php

namespace App\Entity;

use App\Repository\BasketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: BasketRepository::class)]
class Basket
{
    /**
     * @var string
     */
    const STATUS_BASKET = [
        'open' => 'open',
        'closed' => 'closed'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[Gedmo\Timestampable(on:"update")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[Gedmo\Timestampable(on:"update")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'basket', targetEntity: BasketItem::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, BasketItem>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(
        BasketItem $item,
        bool $detailProduct = true,
        string $action = null,
        int $quantity = null
    ): self
    {
        foreach ($this->getItems() as $existingItem) {
            $currentQuantity = $existingItem->getQuantity();
//             We are increasing the amount of the product
            if ($action == 'add' && $existingItem->equals($item)) {
                if ($quantity >= $currentQuantity || $quantity <= $currentQuantity) {
                    $existingItem
                        ->setQuantity($currentQuantity + $quantity)
                        ->setTotal($existingItem->getPrice() * $item->getQuantity());
                }

                return $this;
            }
//          We reduce the quantity or remove the product
            if ($action == 'remove' && $existingItem->equals($item)) {
                if ($quantity < $currentQuantity) {
                    $existingItem
                        ->setQuantity($currentQuantity - $quantity)
                        ->setTotal($item->getPrice() * $item->getQuantity());
                }
                if ($quantity == $currentQuantity || $quantity > $currentQuantity) {
                    $this->removeItem($existingItem);
                }

                return $this;
            }
//          If the product detail page and item already exists, update the quantity
            if ($existingItem->equals($item) && $detailProduct) {
                $existingItem->setQuantity(
                    $existingItem->getQuantity() + $item->getQuantity()
                )
                    ->setPrice($item->getProduct()->getPrice())
                    ->setTotal($item->getProduct()->getPrice() * $existingItem->getQuantity())
                    ->setProduct($item->getProduct());

                return $this;
            }
        }

        $item
            ->setTotal($item->getTotalPrice())
            ->setBasket($this)
            ->setProduct($item->getProduct())
            ->setPrice($item->getProduct()->getPrice());

        $this->items[] = $item;

        return $this;
    }

    public function removeItem(BasketItem $basket): self
    {
        if ($this->items->removeElement($basket)) {
            // set the owning side to null (unless already changed)
            if ($basket->getBasket() === $this) {
                $basket->setBasket(null);
            }
        }

        return $this;
    }

    /**
     * Removes all items from the order.
     *
     * @return $this
     */
    public function removeItems(): self
    {
        foreach ($this->getItems() as $item) {
            $this->removeItem($item);
        }

        return $this;
    }

    /**
     * Calculates the order total.
     *
     * @return float
     */
    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getItems() as $item) {
            $total += $item->getTotal();
        }

        return $total;
    }
}
