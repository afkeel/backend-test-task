<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Redis;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Repository\Entity\Cart;
use Raketa\BackendTestTask\Repository\Entity\Customer;
use Raketa\BackendTestTask\Repository\Entity\CartItem;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;
use Raketa\BackendTestTask\Collection\CartCollection;

class CartManager extends ConnectorFacade
{
    public $logger;

    public function __construct($host, $port, $password, $productRepository)
    {
        parent::__construct($host, $port, $password, 1);
        parent::build();

        $this->productRepository = $productRepository;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart)
    {
        try {
            $this->connector->set(session_id(), $cart);
        } catch (Exception $e) {
            $this->logger->error('Error');
        }
    }

    /**
     * @return CartCollection|bool
     */
    public function getCart()
    {
        try {
            return new CartCollection($this->connector->get(session_id()));
        } catch (Exception $e) {
            $this->logger->error('Error');
        }

        return false;
    }
}
