<?php

namespace Cart\Tests\CartItem\Domain;

use Cart\Cart\Domain\Cart;
use Cart\CartItem\Domain\CartItem;
use Cart\CartItem\Domain\CartItemAmount;
use Cart\CartItem\Domain\Exceptions\ProductsMaxAmountException;
use Cart\Discount\Domain\Discount;
use Cart\Discount\Domain\DiscountFloat;
use Cart\Discount\Domain\DiscountUnit;
use Cart\Product\Domain\Product;
use Cart\Product\Domain\ProductId;
use Cart\Product\Domain\ProductPrice;
use PHPUnit\Framework\TestCase;

class CartItemTest extends TestCase
{

    /** @test */
    public function it_should_error_when_add_max_amount_in_same_product(): void
    {
        $product=$this->getProduct();
        $this->expectException(ProductsMaxAmountException::class);
        $cartItem=new CartItem($product, new CartItemAmount(51));

    }

    public function getProduct(): Product
    {
        $discount = new Discount(
            new DiscountFloat(9),
            new DiscountUnit(3)
        );

        return new Product(
            new ProductId('9faabf63-4ef2-42a5-a95e-3253b5c3613c'),
            new ProductPrice(10),
            $discount
        );
    }

}