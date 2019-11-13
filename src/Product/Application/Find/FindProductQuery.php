<?php

namespace Cart\Product\Application\Find;

use Cart\Shared\Domain\Bus\Query\Query;

class FindProductQuery implements Query
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

}