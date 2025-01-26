<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository\Entity;

readonly final class CartItem
{
    public function __construct(
        public string $uuid,
        public Product $product,
        public float $price,
        public int $quantity,
    ) {
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
