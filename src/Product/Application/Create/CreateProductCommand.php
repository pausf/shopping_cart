<?php

namespace Cart\Product\Application\Create;

use Cart\Shared\Domain\Bus\Command\Command;

class CreateProductCommand implements Command
{

    private $id;
    private $price;
    private $discountPrice;
    private $discountUnit;

    public function __construct(string $id, float $price, float $discountPrice, int $discountUnit)
    {
        $this->id = $id;
        $this->price = $price;
        $this->discountPrice = $discountPrice;
        $this->discountUnit = $discountUnit;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function price(): float
    {
        return $this->price;
    }

    public function discountPrice(): float
    {
        return $this->discountPrice;
    }

    public function discountUnit(): int
    {
        return $this->discountUnit;
    }


}