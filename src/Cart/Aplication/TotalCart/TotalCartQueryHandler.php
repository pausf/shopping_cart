<?php

namespace Cart\Cart\Aplication\TotalCart;

use Cart\Cart\Domain\CartId;
use Cart\Shared\Domain\User\UserId;
use Cart\Shared\Application\QueryBusHandler;

class TotalCartQueryHandler implements QueryBusHandler
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