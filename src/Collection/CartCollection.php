<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Collection;

use Raketa\BackendTestTask\Repository\Entity\Cart;
use Raketa\BackendTestTask\Repository\ProductRepository;

class CartCollection
{
    public function __construct(
        private array $items
    ) {
    }

    public function first(): array
    {
        return array_shift($this->items);
    }

    public function toArray(): array
    {
        return array_map(
            function (Cart $cart) {
                $data = [
                    'uuid' => $cart->getUuid(),
                    'customer' => [
                        'id' => $cart->getCustomer()->getId(),
                        'name' => implode(' ', [
                            $cart->getCustomer()->getLastName(),
                            $cart->getCustomer()->getFirstName(),
                            $cart->getCustomer()->getMiddleName(),
                        ]),
                        'email' => $cart->getCustomer()->getEmail(),
                    ],
                    'payment_method' => $cart->getPaymentMethod()
                ];

                foreach ($cart->getItems() as $item) {
                    $data['items'][] = [
                        'uuid' => $item->getUuid(),
                        'price' => $item->getPrice(),
                        'quantity' => $item->getQuantity(),
                        'product' => [
                            'id' => $item->product->getId(),
                            'uuid' => $item->product->getUuid(),
                            'name' => $item->product->getName(),
                            'thumbnail' => $item->product->getThumbnail(),
                            'price' => $item->product->getPrice(),
                        ],
                    ];
                }

                return $data;
            },
            $this->items
        );
    }
}
