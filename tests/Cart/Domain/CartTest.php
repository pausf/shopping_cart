<?php

namespace Cart\Tests\Cart\Domain;

use Cart\Cart\Domain\Cart;
use Cart\Cart\Domain\Exceptions\ProductDifferentMaxInCartException;
use Cart\CartItem\Domain\CartItem;
use Cart\CartItem\Domain\CartItemAmount;
use Cart\CartItem\Domain\Exceptions\ProductsMaxAmountException;
use Cart\Currency\Domain\Currency;
use Cart\Currency\Domain\CurrencyCode;
use Cart\Currency\Domain\CurrencyExchange;
use Cart\Discount\Domain\Discount;
use Cart\Discount\Domain\DiscountFloat;
use Cart\Discount\Domain\DiscountUnit;
use Cart\Product\Domain\Product;
use Cart\Product\Domain\ProductId;
use Cart\Product\Domain\ProductPrice;
use Cart\Shared\Domain\Exception\Product\InvalidProductException;
use Cart\Tests\Cart\Domain\Factory\CartIdFactory;
use Cart\Tests\Shared\Domain\Factory\UserIdFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as Ramsey;

class CartTest extends TestCase
{


    /** @test */
    public function it_should_count_items_in_cart(): void
    {
        $cart=$this->getCart();
        $product=$this->getProduct();
        $cartItem=new CartItem($product, new CartItemAmount(4));
        $cart->addCartItem($cartItem);

        $this->assertSame(1,$cart->count());

    }

    /** @test */
    public function it_should_throw_exception_when_remove_items_not_exist_in_cart(): void
    {
        $cart=$this->getCart();
        $product=$this->getProduct();
        $this->expectException(InvalidProductException::class);
        $cart->removeCartItem($product->productId());

    }

    /** @test */
    public function it_should_remove_items_in_cart(): void
    {
        $cart=$this->getCart();
        $product=$this->getProduct();
        $cartItem=new CartItem($product, new CartItemAmount(4));
        $cart->addCartItem($cartItem);

        $this->assertSame(1,$cart->count());

        $cart->removeCartItem($product->productId());

        $this->assertSame(0,$cart->count());

    }

    /** @test */
    public function it_should_increment_same_item_in_cart(): void
    {
        $cart=$this->getCart();
        $product=$this->getProduct();

        $cartItem=new CartItem($product, new CartItemAmount(4));
        $cart->addCartItem($cartItem);
        $cartItem=new CartItem($product, new CartItemAmount(4));
        $cart->addCartItem($cartItem);
        $items=$cart->getItem();
        $cartItem=$items['9faabf63-4ef2-42a5-a95e-3253b5c3613c'];
        $this->assertEquals(8,$cartItem->getAmount()->value());

    }


    /** @test */
    public function it_should_apply_discount_item_in_cart(): void
    {
        $cart=$this->getCart();
        $product=$this->getProduct();

        for ($i = 0; $i <= 3; $i++) {
            $cartItem=new CartItem($product, new CartItemAmount(1));
            $cart->addCartItem($cartItem);
        }

        $items=$cart->getItem();
        $cartItem=$items['9faabf63-4ef2-42a5-a95e-3253b5c3613c'];

        $this->assertEquals(36,$cartItem->applyDiscountPrice());

    }

    /** @test */
    public function it_should_error_when_add_max_item_in_cart(): void
    {
        $cart=$this->getCart();

        for ($i = 0; $i <= 10; $i++) {
            $productRandom=$this->getProductRandom();
            $cartItem=new CartItem($productRandom, new CartItemAmount(4));
            $cart->addCartItem($cartItem);
        }

        $productRandom=$this->getProductRandom();
        $cartItem=new CartItem($productRandom, new CartItemAmount(4));
        $this->expectException(ProductDifferentMaxInCartException::class);
        $cart->addCartItem($cartItem);

    }

    /** @test */
    public function it_should_error_when_increment_amount_to_max(): void
    {
        $cart=$this->getCart();
        for ($i = 0; $i < 5; $i++) {
            $product=$this->getProduct();
            $cartItem=new CartItem($product, new CartItemAmount(10));
            $cart->addCartItem($cartItem);
        }
        $product=$this->getProduct();
        $cartItem=new CartItem($product, new CartItemAmount(1));
        $this->expectException(ProductsMaxAmountException::class);
        $cart->addCartItem($cartItem);

    }

    /** @test */
    public function it_should_get_total_cart(): void
    {
        $cart=$this->getCart();
        $product=$this->getProduct();
        $cartItem=new CartItem($product, new CartItemAmount(2));
        $cart->addCartItem($cartItem);
        $total=$cart->getTotal();
        $this->assertEquals(20,$total['EUR']);
    }

    /** @test */
    public function it_should_get_total_cart_with_EUR(): void
    {
        $cart=$this->getCartCurrency();
        $product=$this->getProduct();
        $cartItem=new CartItem($product, new CartItemAmount(2));
        $cart->addCartItem($cartItem);
        $total=$cart->getTotal();
        $this->assertEquals(20,$total['EUR']);
    }

    public function it_should_get_total_cart_with_EUR_and_discount_apply(): void
    {
        $cart=$this->getCartCurrency();
        $product=$this->getProduct();
        $cartItem=new CartItem($product, new CartItemAmount(3));
        $cart->addCartItem($cartItem);
        $total=$cart->getTotal();
        $this->assertEquals(27,$total['DISCOUNT']);
    }

    /** @test */
    public function it_should_get_total_cart_with_other_currency(): void
    {
        $cart=$this->getCartCurrency();
        $product=$this->getProduct();
        $cartItem=new CartItem($product, new CartItemAmount(2));
        $cart->addCartItem($cartItem);
        $total=$cart->getTotal();
        $this->assertEquals(22,$total['USD']);
    }

    /** @test */
    public function it_should_get_total_cart_with_other_currency_and_discount_apply(): void
    {
        $cart=$this->getCartCurrency();
        $product=$this->getProduct();
        $cartItem=new CartItem($product, new CartItemAmount(3));
        $cart->addCartItem($cartItem);
        $total=$cart->getTotal();
        $this->assertEquals(29.7,$total['DISCOUNT_USD']);
    }

    /** @test */
    public function it_should_create_cart(): void
    {
        $cart=$this->getCartCurrency();
        $this->assertInstanceOf(Cart::class, $cart);
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

    public function getProductRandom(): Product
    {
        $discount = new Discount(
            new DiscountFloat(10.0),
            new DiscountUnit(2)
        );

        return new Product(
            new ProductId(Ramsey::uuid4()->toString()),
            new ProductPrice(12.12),
            $discount
        );
    }

    public function getCartCurrency(): Cart
    {
        return Cart::create(
            CartIdFactory::create(Ramsey::uuid4()->toString()),
            UserIdFactory::create(Ramsey::uuid4()->toString()),
            new Currency(new CurrencyCode('USD'),new CurrencyExchange(1.10)));
    }
    public function getCart(): Cart
    {
        return Cart::create(
            CartIdFactory::create(Ramsey::uuid4()->toString()),
            UserIdFactory::create(Ramsey::uuid4()->toString()));
    }
}