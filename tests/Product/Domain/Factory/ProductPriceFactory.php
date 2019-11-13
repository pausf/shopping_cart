<?php

namespace Cart\Tests\Product\Domain\Factory;

use Cart\Product\Domain\ProductPrice;

class ProductPriceFactory
{
    public static function create($price): ProductPrice
    {
        return new ProductPrice($price);
    }
}