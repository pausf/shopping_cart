<?php

namespace Cart\Tests\Currency\Domain\ValueObject;

use Cart\Currency\Domain\CurrencyCode;
use Cart\Currency\Domain\Exceptions\CurrencyCodeInvalidCodeException;
use PHPUnit\Framework\TestCase;

class CurrencyCodeTest extends TestCase
{

    /** @test */
    public function it_should_valid_not_valid_regex_currency_code(): void
    {
        $this->expectException(CurrencyCodeInvalidCodeException::class);
        $currencyCode=new CurrencyCode('12A');
    }

    /** @test */
    public function it_should_valid_regex_currency_code(): void
    {
        $currencyCode=new CurrencyCode('USD');
        $this->assertInstanceOf(CurrencyCode::class, $currencyCode);
    }

}