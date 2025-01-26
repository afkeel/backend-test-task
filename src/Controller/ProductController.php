<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\Http\Response;

class ProductController
{
    public function __construct(
        private ProductRepository $productRepository,
        private Response $response
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $products = $this->productRepository->getByCategory($rawRequest['category']);
        
        return $this->response->json($products->toArray(), 200, ['status' => 'success']);
    }
}
