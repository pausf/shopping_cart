<?php

namespace Cart\Product\Application\Create;

use Cart\Discount\Domain\Discount;
use Cart\Discount\Domain\DiscountFloat;
use Cart\Discount\Domain\DiscountUnit;
use Cart\Product\Domain\Product;
use Cart\Product\Domain\ProductId;
use Cart\Product\Domain\ProductPrice;
use Cart\Tests\Shared\Application\QueryBusHandlerInterface;

class CreateProductCommandHandler implements QueryBusHandlerInterface
{
    private $service;

    public function __construct(CreateProduct $service)
    {
        $this->service = $service;
    }

    public function __invoke(CreateProductCommand $command)
    {
        $discount = new Discount(
            new DiscountFloat($command->discountPrice()),
            new DiscountUnit($command->discountUnit())
        );

        $product = new Product(
            new ProductId($command->id()),
            new ProductPrice($command->price()),
            $discount
        );

        $this->service->create($product);
    }
}