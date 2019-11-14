<?php

namespace Cart\Product\Application\Find;

use Cart\Product\Domain\ProductId;
use Cart\Product\Domain\ProductRepository;

class FindProduct
{
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function find(ProductId $id)
    {
        $this->repository->find($id);
    }

}