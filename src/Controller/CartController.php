<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Redis\CartManager;
use Raketa\BackendTestTask\Collection\CartCollection;
use Raketa\BackendTestTask\Domain\Repository\ProductRepository;
use Raketa\BackendTestTask\Http\Response;

class CartController
{
    public function __construct(
        private CartManager $cartManager,
        private ProductRepository $productRepository,
        private Response $response
    ) {
    }

    public function get(): ResponseInterface
    {
        $cart = $this->cartManager->getCart();

        if ($cart) {
            $data = $cart->toArray()->first();
            
            $total = 0;
            foreach ($data['items'] as &$item) {
                $item['sum'] = $item['price'] * $item['quantity'];
                $total += $item['sum'];
            }
    
            $data['total'] = $total;
            $code = 200;
        } else {
            $code = 404;
            $message = ['message' => 'Cart not found'];
        }

        return $this->response->json($data ?? [], $code, $message ?? []);
    }

    public function update(RequestInterface $request): ResponseInterface
    {
        $cart = $this->cartManager->getCart();

        if($cart)
        {
            $rawRequest = json_decode($request->getBody()->getContents(), true);
            $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

            $cart->addItem(new CartItem(
                Uuid::uuid4()->toString(),
                $product,
                $product->getPrice(),
                $rawRequest['quantity'],
            ));
    
            $this->cartManager->saveCart($cart); // забыли сохранить обратно в редис

            $data = ['cart' => $cart->toArray()->first()];
            $message = ['status' => 'success'];
        }

        return $this->response->json($data ?? [], $code, $message ?? []);
    }
}
