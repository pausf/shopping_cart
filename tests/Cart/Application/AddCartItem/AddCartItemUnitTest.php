<?php

namespace Cart\Tests\Cart\Application\AddCartItem;

use Cart\Cart\Aplication\AddCartItem\AddCartItem;
use Cart\Cart\Aplication\AddCartItem\AddCartItemCommand;
use Cart\Cart\Aplication\AddCartItem\AddCartItemCommandHandler;
use Cart\Tests\Cart\CartUnitTest;
use Cart\Tests\Cart\Domain\Factory\CartIdFactory;
use Cart\Tests\Product\Domain\Factory\ProductIdFactory;
use Cart\Tests\Shared\Domain\Factory\UserIdFactory;
use Ramsey\Uuid\Uuid as Ramsey;

class AddCartItemUnitTest extends CartUnitTest
{



    /** @test */
    public function it_should_add_car_item_in_cart(): void
    {

        $cartId=Ramsey::uuid4()->toString();
        $userId=Ramsey::uuid4()->toString();
        $productId='32416361-b8ec-4e00-84cc-b75b50945e21';
        $amount=4;


        $command=new AddCartItemCommand($cartId,$productId,$userId,$amount);
        $handler=new AddCartItemCommandHandler(new AddCartItem($this->cartRepository,$this->productRepository));

        $productId=ProductIdFactory::create($command->getIdProduct());
        $cartId=CartIdFactory::create($command->getIdCart());
        $userId=UserIdFactory::create($command->getIdUser());

        $product=$this->getProduct();
        $cart=$this->getCart($cartId,$userId);


        $this->cartRepository
            ->method('get')
            ->with($cart->getCartId())
            ->willReturn($cart);

        $this->productRepository
            ->method('find')
            ->with($productId)
            ->willReturn($product);

        $this->cartRepository
            ->method('update')
            ->with($cart);


        $this->dispatch($command,$handler);

    }

}