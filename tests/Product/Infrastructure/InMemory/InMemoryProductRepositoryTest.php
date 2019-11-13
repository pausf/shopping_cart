<?php

namespace Cart\Tests\Product\Infrastructure\InMemory;

use Cart\Discount\Domain\Discount;
use Cart\Discount\Domain\DiscountFloat;
use Cart\Discount\Domain\DiscountUnit;
use Cart\Product\Domain\Product;
use Cart\Product\Domain\ProductId;
use Cart\Product\Domain\ProductPrice;
use Cart\Product\Infrastructure\InMemory\InMemoryProductRepository;
use PHPUnit\Framework\TestCase;

class InMemoryProductRepositoryTest extends TestCase
{

    public function getProduct(): Product
    {

        $discount = new Discount(
            new DiscountFloat(10.0),
            new DiscountUnit(2)
        );

        return new Product(
            new ProductId('32416361-b8ec-4e00-84cc-b75b50945e21'),
            new ProductPrice(12.12),
            $discount
        );
    }

    /** @test */
    public function it_should_create_a_product_in_memory(): void
    {

        $repository=new InMemoryProductRepository();
        $repository->create($this->getProduct());
        $this->assertSame(1,$repository->count());
    }


    public function it_should_find_a_product_in_memory(): void
    {
        $repository=new InMemoryProductRepository();
        $product=$this->getProduct();
        $repository->create($product);
        $this->assertSame($product,$repository->find($product->productId()));
    }
}
