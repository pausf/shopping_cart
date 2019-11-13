<?php

namespace Cart\Product\Application\Create;

use Cart\Product\Domain\Product;
use Cart\Product\Domain\ProductRepository;

class CreateProduct
{

    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Product $product)
    {
        $this->repository->create($product);
    }
}