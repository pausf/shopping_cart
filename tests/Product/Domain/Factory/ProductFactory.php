<?php

namespace Cart\Tests\Product\Domain\Factory;

use Cart\Discount\Domain\Discount;
use Cart\Product\Domain\Product;
use Cart\Product\Domain\ProductId;
use Cart\Product\Domain\ProductPrice;

class ProductFactory
{
    public static function create(ProductId $id , ProductPrice $price , Discount $discount):Product
    {
        return new Product($id,$price,$discount);
    }
}