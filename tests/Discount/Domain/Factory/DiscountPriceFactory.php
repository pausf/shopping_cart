<?php

namespace Cart\Tests\Discount\Domain\Factory;

use Cart\Discount\Domain\DiscountFloat;

class DiscountPriceFactory
{
    public static function create($price)
    {
        return new DiscountFloat($price);
    }
}