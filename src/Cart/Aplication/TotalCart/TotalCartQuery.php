<?php

namespace Cart\Cart\Aplication\TotalCart;

use Cart\Shared\Domain\Bus\Query\Query;

class TotalCartQuery implements Query
{

    private $idCart;
    private $idUser;

    public function __construct($idCart, $idUser)
    {
        $this->idCart = $idCart;
        $this->idUser = $idUser;
    }

    public function getIdCart()
    {
        return $this->idCart;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

}