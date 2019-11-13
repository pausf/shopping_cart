<?php

namespace Cart\Cart\Aplication\RemoveCartItem;

use Cart\Shared\Domain\Bus\Command\Command;

class RemoveCartItemCommand implements Command
{

    private $idCart;
    private $idProduct;
    private $idUser;


    public function __construct($idCart, $idProduct, $idUser)
    {
        $this->idCart = $idCart;
        $this->idProduct = $idProduct;
        $this->idUser = $idUser;
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


}