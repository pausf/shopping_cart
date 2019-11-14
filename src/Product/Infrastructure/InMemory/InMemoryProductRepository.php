<?php

namespace Cart\Product\Infrastructure\InMemory;

use Cart\Product\Domain\Product;
use Cart\Product\Domain\ProductId;
use Cart\Product\Domain\ProductRepository;

class InMemoryProductRepository implements ProductRepository
{

    private $product = [];


    public function create(Product $product)
    {
        $this->product[$product->productId()->value()] = $product;
    }

    public function find(ProductId $productId): Product
    {
        return $this->product[$productId->value()];
    }

    public function count()
    {
        return count($this->product);
    }
}
