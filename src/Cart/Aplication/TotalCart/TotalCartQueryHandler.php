<?php

namespace Cart\Cart\Aplication\TotalCart;

use Cart\Cart\Domain\CartId;
use Cart\Shared\Domain\User\UserId;
use Cart\Tests\Shared\Application\QueryBusHandlerInterface;

class TotalCartQueryHandler implements QueryBusHandlerInterface
{

    private $service;

    public function __construct(TotalCart $service)
    {
        $this->service = $service;
    }

    public function __invoke(TotalCartQuery $query)
    {

        return $this->service->total(
            new CartId($query->getIdCart()),
            new UserId($query->getIdUser())
        );

    }

}