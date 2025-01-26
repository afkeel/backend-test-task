<?php

namespace Raketa\BackendTestTask\Resource;

use Raketa\BackendTestTask\Repository\Entity\Product;
use Raketa\BackendTestTask\Repository\ProductRepository;

class ProductCollection
{
    public function __construct(
        private array $items,
    ) {}

    public function toArray(): array
    {
        return array_map(
            fn (Product $product) => [
                'id' => $product->getId(),
                'uuid' => $product->getUuid(),
                'category' => $product->getCategory(),
                'description' => $product->getDescription(),
                'thumbnail' => $product->getThumbnail(),
                'price' => $product->getPrice(),
            ],
            $this->items
        );
    }
}
