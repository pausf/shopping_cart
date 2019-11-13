<?php

namespace Cart\Tests\Currency\Infrastructure;

use Cart\Currency\Domain\CurrencyCode;
use Cart\Currency\Domain\Exceptions\InvalidExhangeRateExceptions;
use Cart\Currency\Infrastructure\Request\ExchangeRatesApiRepository;
use PHPUnit\Framework\TestCase;

class ExchangeRatesApiRepositoryTest extends TestCase
{

    const STATUS_OK=200;
    /** @test */
    public function it_should_get_a_exchange_rate(): void
    {
        $repository=new ExchangeRatesApiRepository();
        $rate=$repository->get(new CurrencyCode('USD'));
        $this->assertTrue(is_numeric($rate));

    }


    /** @test */
    public function it_should_get_a_expectation_in_error_rate(): void
    {
        $repository=new ExchangeRatesApiRepository();
        $this->expectException(InvalidExhangeRateExceptions::class);
        $rate=$repository->get(new CurrencyCode('ASE'));
    }

    /** @test */
    public function it_should_get_a_response_200(): void
    {
        $repository=new ExchangeRatesApiRepository();
        $this->assertEquals($repository->status(),self::STATUS_OK);
    }
}