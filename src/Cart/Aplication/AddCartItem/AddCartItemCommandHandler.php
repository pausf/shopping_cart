<?php

namespace Cart\Cart\Aplication\AddCartItem;

use Cart\Cart\Domain\CartId;
use Cart\CartItem\Domain\CartItemAmount;
use Cart\Product\Domain\ProductId;
use Cart\Shared\Domain\User\UserId;
use Cart\Shared\Application\CommandBusHandler;

final class AddCartItemCommandHandler implements CommandBusHandler
{
    private $adder;

    public function __construct(AddCartItem $service)
    {
        $this->adder = $service;
    }

    public function __invoke(AddCartItemCommand $command)
    {
        $this->adder->add(
            new CartId($command->getIdCart()),
            new UserId($command->getIdUser()),
            new ProductId($command->getIdProduct()),
            new CartItemAmount($command->getAmount())
        );
    }
}