<?php

namespace Cart\Cart\Aplication\RemoveCartItem;

use Cart\Cart\Domain\CartId;
use Cart\Product\Domain\ProductId;
use Cart\Shared\Domain\User\UserId;
use Cart\Shared\Application\CommandBusHandler;

class RemoveCartItemCommandHandler implements CommandBusHandler
{

    private $service;


    public function __construct(RemoveCartItem $service)
    {
        $this->service = $service;
    }


    public function __invoke(RemoveCartItemCommand $command)
    {

        $this->service->remove(
            new CartId($command->getIdCart()),
            new UserId($command->getIdUser()),
            new ProductId($command->getIdProduct())
        );
    }
}