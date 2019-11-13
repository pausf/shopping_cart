<?php

namespace Cart\Tests\Product\Domain\Factory;

use Cart\Product\Domain\ProductId;

class ProductIdFactory
{
    public static function create($id):ProductId
    {
        return new ProductId($id);
    }
}