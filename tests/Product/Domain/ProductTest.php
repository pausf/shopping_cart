<?php

namespace Cart\Tests\Product\Domain;

use Cart\Product\Domain\Product;
use Cart\Tests\Product\ProductUnitTest;

class ProductTest extends ProductUnitTest
{

    /** @test */
    public function it_should_create_cart(): void
    {
        $product=$this->getProduct();
        $this->assertInstanceOf(Product::class, $product);
    }
}