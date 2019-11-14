<?php

namespace Cart\CartItem\Domain;

use Cart\CartItem\Domain\Exceptions\ProductsMaxAmountException;
use Cart\Shared\Domain\ValueObject\IntNumber;

class CartItemAmount extends IntNumber
{
    const MAX_SAME_ITEMS = 50;

    function __construct($amount)
    {

        parent::__construct($amount);

        $this->guardMaxItemAmount($amount);
    }


    private function guardMaxItemAmount($amount)
    {
        if ($amount > self::MAX_SAME_ITEMS) {
            throw new ProductsMaxAmountException('Maximum amount of the same product');
        }

    }
}