<?php

namespace Cart\Tests\Cart\Domain\Factory;

use Cart\Cart\Domain\CartId;

class CartIdFactory
{
    public static function create($id):CartId
    {
        return new CartId($id);
    }
}