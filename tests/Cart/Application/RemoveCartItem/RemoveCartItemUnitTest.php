<?php

namespace Cart\Tests\Cart\Application\RemoveCartItem;


use Cart\Cart\Aplication\RemoveCartItem\RemoveCartItem;
use Cart\Cart\Aplication\RemoveCartItem\RemoveCartItemCommand;
use Cart\Cart\Aplication\RemoveCartItem\RemoveCartItemCommandHandler;
use Cart\CartItem\Domain\CartItem;
use Cart\CartItem\Domain\CartItemAmount;
use Cart\Tests\Cart\CartUnitTest;
use Cart\Tests\Cart\Domain\Factory\CartIdFactory;
use Cart\Tests\Shared\Domain\Factory\UserIdFactory;
use Ramsey\Uuid\Uuid as Ramsey;

class RemoveCartItemUnitTest extends CartUnitTest
{

    /** @test */
    public function it_should_delete_car_item_in_cart(): void
    {

        $cartId=Ramsey::uuid4()->toString();
        $userId=Ramsey::uuid4()->toString();
        $productId='32416361-b8ec-4e00-84cc-b75b50945e21';


        $command=new RemoveCartItemCommand($cartId,$productId,$userId);
        $handler=new RemoveCartItemCommandHandler(new RemoveCartItem($this->cartRepository));
        $cartId=CartIdFactory::create($command->getIdCart());
        $userId=UserIdFactory::create($command->getIdUser());


        $product=$this->getProduct();
        $cart=$this->getCart($cartId,$userId);



        $cart->addCartItem(new CartItem($product,new CartItemAmount(4)));

        $this->cartRepository
            ->method('get')
            ->with($cart->getCartId())
            ->willReturn($cart);



        $this->cartRepository
            ->method('update')
            ->with($cart);

        $this->dispatch($command,$handler);

    }

}