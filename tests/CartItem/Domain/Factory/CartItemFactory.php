<?php

namespace Cart\Tests\CartItem\Domain\Factory;

use Cart\CartItem\Domain\CartItemAmount;

class CartItemFactory
{
    public static function create($amount):CartItemAmount
    {
        return new CartItemAmount($amount);
    }

}