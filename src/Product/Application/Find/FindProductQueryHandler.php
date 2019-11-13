<?php

namespace Cart\Product\Application\Find;

use Cart\Product\Domain\ProductId;

class FindProductQueryHandler
{

    private $service;


    public function __construct(FindProduct $service)
    {
        $this->service = $service;
    }

    public function __invoke(FindProductQuery $command)
    {

        $this->service->find(new ProductId($command->id()));

    }
}