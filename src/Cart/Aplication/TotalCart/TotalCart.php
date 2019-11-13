<?php

namespace Cart\Cart\Aplication\TotalCart;

use Cart\Cart\Domain\CartId;
use Cart\Cart\Domain\CartRepository;
use Cart\Cart\Domain\Exceptions\CartNotExistException;
use Cart\Shared\Domain\User\UserId;

class TotalCart
{

    private $repository;

    public function __construct(CartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function total(CartId $cartId , UserId $userId)
    {
        return $this->repository->getTotal($cartId);
    }

}