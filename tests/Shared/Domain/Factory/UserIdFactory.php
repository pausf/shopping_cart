<?php

namespace Cart\Tests\Shared\Domain\Factory;

use Cart\Shared\Domain\User\UserId;

class UserIdFactory
{
    public static function create($id):UserId
    {
        return new UserId($id);
    }
}