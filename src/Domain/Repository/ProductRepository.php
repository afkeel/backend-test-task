<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Repository\Entity\Product;
use Raketa\BackendTestTask\Collection\ProductCollection;
use Exception;

class ProductRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByUuid(string $uuid): Product
    {
        $row = $this->connection->fetchAssociative(
            "SELECT * FROM products WHERE uuid = ?", [$uuid],
        );

        if (empty($row)) {
            throw new Exception('Product not found');
        }

        return $this->make($row);
    }

    public function getByCategory(string $category): ProductCollection
    {
        return new ProductCollection(
            array_map(
                fn (array $row): Product => $this->make($row),
                $this->connection->fetchAllAssociative(
                    "SELECT * FROM products WHERE is_active = 1 AND category = ?", [$category]
                )
            )
        );
    }

    public function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            boolval($row['is_active']),
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
