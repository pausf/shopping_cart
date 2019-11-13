<?php

namespace Cart\Cart\Aplication\AddCartItem;


use Cart\Cart\Domain\CartId;
use Cart\CartItem\Domain\CartItemAmount;
use Cart\Product\Domain\ProductId;
use Cart\Shared\Domain\User\UserId;
use Cart\Tests\Shared\Application\CommandBusHandlerInterface;

class AddCartItemCommandHandler implements CommandBusHandlerInterface
{

    private $service;

    public function __construct(AddCartItem $service)
    {
        $this->service = $service;
    }

    public function __invoke(AddCartItemCommand $command)
    {

       $this->service->add(
           new CartId($command->getIdCart()),
           new UserId($command->getIdUser()),
           new ProductId($command->getIdProduct()),
           new CartItemAmount($command->getAmount())
       );
    }
}