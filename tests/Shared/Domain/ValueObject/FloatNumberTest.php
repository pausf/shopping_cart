<?php

namespace Cart\Tests\Shared\Domain\ValueObject;

use Cart\Shared\Domain\Exception\NumberNotNegativeOrZero;
use Cart\Shared\Domain\ValueObject\FloatNumber;
use PHPUnit\Framework\TestCase;

class FloatNumberTest extends TestCase
{

    /** @test */
    public function it_should_negative_valid_float_number(): void
    {
        $this->expectException(NumberNotNegativeOrZero::class);
        $number=new FloatNumber(-1);
    }

    /** @test */
    public function it_should_valid_number(): void
    {
        $number=new FloatNumber(12.2);
        $this->assertInstanceOf(FloatNumber::class, $number);
    }
}