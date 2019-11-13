<?php

namespace Cart\Tests\Cart\Application\TotalCart;

use Cart\Cart\Aplication\TotalCart\TotalCart;
use Cart\Cart\Aplication\TotalCart\TotalCartQuery;
use Cart\Cart\Aplication\TotalCart\TotalCartQueryHandler;
use Cart\CartItem\Domain\CartItem;
use Cart\CartItem\Domain\CartItemAmount;
use Cart\Tests\Cart\CartUnitTest;
use Cart\Tests\Cart\Domain\Factory\CartIdFactory;
use Cart\Tests\Shared\Domain\Factory\UserIdFactory;
use Ramsey\Uuid\Uuid as Ramsey;

class TotalCartItemUnitTest extends CartUnitTest
{

    /** @test */
    public function it_should_get_total_car_in_cart(): void
    {
        $cartId=Ramsey::uuid4()->toString();
        $userId=Ramsey::uuid4()->toString();

        $query=new TotalCartQuery($cartId,$userId);
        $handler=new TotalCartQueryHandler(new TotalCart($this->cartRepository));
        $cartId=CartIdFactory::create($query->getIdCart());
        $userId=UserIdFactory::create($query->getIdUser());


        $product=$this->getProduct();
        $cart=$this->getCart($cartId,$userId);

        $cart->addCartItem(new CartItem($product,new CartItemAmount(2)));

        $response="10";
        $this->cartRepository
            ->method('getTotal')
            ->with($cart->getCartId())
            ->willReturn($response);

        $this->assertEquals($response,$this->ask($query,$handler));

    }



}