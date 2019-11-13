<?php


namespace Cart\Tests\Product\Application\Create;

use Cart\Product\Application\Create\CreateProduct;
use Cart\Product\Application\Create\CreateProductCommand;
use Cart\Product\Application\Create\CreateProductCommandHandler;
use Cart\Tests\Discount\Domain\Factory\DiscountFactory;
use Cart\Tests\Discount\Domain\Factory\DiscountPriceFactory;
use Cart\Tests\Discount\Domain\Factory\DiscountUnitFactory;
use Cart\Tests\Product\Domain\Factory\ProductFactory;
use Cart\Tests\Product\Domain\Factory\ProductPriceFactory;
use Cart\Tests\Product\Domain\Factory\ProductIdFactory;
use Cart\Tests\Product\ProductUnitTest;
use Ramsey\Uuid\Uuid as Ramsey;

class CreateProductUnitTest extends ProductUnitTest
{

    /** @test */
    public function it_should_create_a_product(): void
    {
        $idProduct=Ramsey::uuid4()->toString();
        $command=new CreateProductCommand($idProduct,12.23,9.12,2);
        $handler=new CreateProductCommandHandler(new CreateProduct($this->productRepository));

        $discountPrice=DiscountPriceFactory::create($command->discountPrice());
        $discountUnit=DiscountUnitFactory::create($command->discountUnit());
        $discount=DiscountFactory::create($discountPrice,$discountUnit);

        $productId=ProductIdFactory::create($command->id());
        $productPrice=ProductPriceFactory::create($command->price());
        $product=ProductFactory::create($productId,$productPrice,$discount);

        $this->productRepository
            ->method('find')
            ->with($productId)
            ->willReturn($product);


        $this->dispatch($command,$handler);
    }



}