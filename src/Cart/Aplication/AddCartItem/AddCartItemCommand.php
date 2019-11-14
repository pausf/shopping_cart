<?php

namespace Cart\Cart\Aplication\AddCartItem;

use Cart\Shared\Domain\Bus\Command\Command;

class AddCartItemCommand implements Command
{
    private $idCart;
    private $idProduct;
    private $idUser;
    private $amount;

    public function __construct($idCart, $idProduct, $idUser, $amount)
    {
        $this->idCart = $idCart;
        $this->idProduct = $idProduct;
        $this->idUser = $idUser;
        $this->amount = $amount;
    }

    public function getIdCart()
    {
        return $this->idCart;
    }

    public function getIdProduct()
    {
        return $this->idProduct;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function getAmount()
    {
        return $this->amount;
    }

}