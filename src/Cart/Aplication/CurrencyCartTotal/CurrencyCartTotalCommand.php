<?php

namespace Cart\Cart\Aplication\CurrencyCartTotal;

use Cart\Shared\Domain\Bus\Command\Command;
use Cart\Shared\Domain\Bus\Query\Query;

class CurrencyCartTotalCommand implements Command
{

    private $idCart;
    private $idUser;
    private $codeCurrency;

    public function __construct($idCart, $idUser, $codeCurrency)
    {
        $this->idCart = $idCart;
        $this->idUser = $idUser;
        $this->codeCurrency = $codeCurrency;
    }

    public function getIdCart()
    {
        return $this->idCart;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }


    public function getCodeCurrency()
    {
        return $this->codeCurrency;
    }

}