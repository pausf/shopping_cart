<?php

namespace Cart\Cart\Infrastructure\InMemory;

use Cart\Cart\Domain\Cart;
use Cart\Cart\Domain\CartId;
use Cart\Cart\Domain\CartRepository;
use Cart\Cart\Domain\Exceptions\CartNotExistException;

class InMemoryCartRepository implements CartRepository
{

    private $cart=[];


    public function update(Cart $cart)
    {
        return $this->cart[$cart->getCartId()->value()]=$cart;
    }

    public function get(CartId $cartId)
    {
      return isset($this->cart[$cartId->value()])? $this->cart[$cartId->value()] : null;
    }

    public function getTotal(CartId $cartId):array
    {
        $cart=$this->cart[$cartId->value()];

        if (null === $cart) {
            throw new CartNotExistException($cartId->value().' not found');
        }
        return $cart->getTotal();
    }
}