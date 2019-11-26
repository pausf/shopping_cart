<?php

namespace Cart\Cart\Aplication\CurrencyCartTotal;

use Cart\Cart\Domain\CartId;
use Cart\Currency\Domain\CurrencyCode;
use Cart\Shared\Domain\User\UserId;
use Cart\Shared\Application\CommandBusHandler;

class CurrencyCartTotalCommandHandler implements CommandBusHandler
{

    private $service;

    public function __construct(CurrencyCartTotal $service)
    {
        $this->service = $service;
    }

    public function __invoke(CurrencyCartTotalCommand $command)
    {

        $this->service->changeCurrency(
            new CartId($command->getIdCart()),
            new UserId($command->getIdUser()),
            new CurrencyCode($command->getCodeCurrency())
        );

    }
}