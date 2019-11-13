<?php

namespace Cart\Tests\Discount\Domain\Factory;

use Cart\Discount\Domain\Discount;
use Cart\Discount\Domain\DiscountFloat;
use Cart\Discount\Domain\DiscountUnit;

class DiscountFactory
{
    public static function create(DiscountFloat $price , DiscountUnit $unit):Discount
    {
        return new Discount($price,$unit);
    }
}