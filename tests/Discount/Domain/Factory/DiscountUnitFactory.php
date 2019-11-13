<?php

namespace Cart\Tests\Discount\Domain\Factory;

use Cart\Discount\Domain\DiscountUnit;

class DiscountUnitFactory
{
    public static function create($unit):DiscountUnit
    {
        return new DiscountUnit($unit);
    }

}