<?php

namespace Cart\Tests\Currency\Domain\Factory;

use Cart\Currency\Domain\CurrencyCode;

class CurrencyCodeFactory
{
    public static function create($code):CurrencyCode
    {
        return new CurrencyCode($code);
    }
}