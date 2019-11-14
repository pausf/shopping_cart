<?php

namespace Cart\Cart\Domain;

interface CartRepository
{
    public function update(Cart $cart);

    public function get(CartId $id);

    public function getTotal(CartId $id);
}