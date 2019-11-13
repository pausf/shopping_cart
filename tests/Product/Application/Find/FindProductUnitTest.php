<?php

namespace Cart\Tests\Product\Application\Find;


use Cart\Product\Application\Find\FindProduct;
use Cart\Product\Application\Find\FindProductQuery;
use Cart\Product\Application\Find\FindProductQueryHandler;
use Cart\Product\Domain\ProductId;
use Cart\Tests\Discount\Domain\Factory\DiscountFactory;
use Cart\Tests\Discount\Domain\Factory\DiscountPriceFactory;
use Cart\Tests\Discount\Domain\Factory\DiscountUnitFactory;
use Cart\Tests\Product\Domain\Factory\ProductFactory;
use Cart\Tests\Product\Domain\Factory\ProductPriceFactory;
use Cart\Tests\Product\ProductUnitTest;
use Ramsey\Uuid\Uuid as Ramsey;

class FindProductUnitTest extends ProductUnitTest
{


    /** @test */
    public function it_should_find_a_product(): void
    {
        $idProduct=Ramsey::uuid4()->toString();
        $query=new FindProductQuery($idProduct);
        $handler=new FindProductQueryHandler(new FindProduct($this->productRepository));

        $discountPrice=DiscountPriceFactory::create(10.12);
        $discountUnit=DiscountUnitFactory::create(2);
        $discount=DiscountFactory::create($discountPrice,$discountUnit);

        $productId=new ProductId($idProduct);
        $productPrice=ProductPriceFactory::create(12.12);
        $product=ProductFactory::create($productId,$productPrice,$discount);


        $this->productRepository
            ->method('find')
            ->with($productId)
            ->willReturn($product);

        $this->ask($query,$handler);

    }

}