<?php

namespace Cart\Cart\Aplication\RemoveCartItem;

use Cart\Cart\Domain\Cart;
use Cart\Cart\Domain\CartId;
use Cart\Cart\Domain\CartRepository;
use Cart\Cart\Domain\Exceptions\CartNotExistException;
use Cart\Product\Domain\ProductId;
use Cart\Shared\Domain\User\UserId;

class RemoveCartItem
{

    private $repository;

    public function __construct(CartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function remove(CartId $cartId, UserId $userId, ProductId $productId)
    {

        $cart = $this->repository->get($cartId);

        if (null === $cart) {
            throw new CartNotExistException($cartId->value() . ' not found');
        }

        $cart->removeCartItem($productId);

        $this->repository->update($cart);

    }

}