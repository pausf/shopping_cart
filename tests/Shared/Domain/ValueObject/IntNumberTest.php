<?php

namespace Cart\Tests\Shared\Domain\ValueObject;

use Cart\Shared\Domain\Exception\InvalidNumberArgumentException;
use Cart\Shared\Domain\Exception\NumberNotNegativeOrZero;
use Cart\Shared\Domain\ValueObject\IntNumber;
use PHPUnit\Framework\TestCase;

class IntNumberTest extends TestCase
{
    /** @test */
    public function it_should_not_valid_number(): void
    {
        $this->expectException(InvalidNumberArgumentException::class);
        $number=new IntNumber('213123123');
    }

    /** @test */
    public function it_should_negative_valid_number(): void
    {
        $this->expectException(NumberNotNegativeOrZero::class);
        $number=new IntNumber(-1);
    }

    /** @test */
    public function it_should_zero_valid_number(): void
    {
        $this->expectException(NumberNotNegativeOrZero::class);
        $number=new IntNumber(0);
    }

    /** @test */
    public function it_should_valid_number(): void
    {
        $number=new IntNumber(12);
        $this->assertInstanceOf(IntNumber::class, $number);
    }
}