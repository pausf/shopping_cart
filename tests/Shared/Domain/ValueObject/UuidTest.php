<?php

namespace Cart\Tests\Shared\Domain\ValueObject;

use Cart\Shared\Domain\Exception\UuidNotValidException;
use Cart\Shared\Domain\ValueObject\Uuid;
use PHPUnit\Framework\TestCase;


class UuidTest extends TestCase
{
    /** @test */
    public function it_should_not_valid_uuid(): void
    {
        $this->expectException(UuidNotValidException::class);
        $uuid=new Uuid('213123123');
    }

}