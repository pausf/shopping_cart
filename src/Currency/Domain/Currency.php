<?php

namespace Cart\Currency\Domain;

class Currency
{

    private $currencyCode;
    private $currencyExchange;

    public function __construct(CurrencyCode $currencyCode, CurrencyExchange $currencyExchange)
    {
        $this->currencyCode = $currencyCode;
        $this->currencyExchange = $currencyExchange;
    }

    public static function create(CurrencyCode $currencyCode, CurrencyExchange $currencyExchange): Currency
    {
        return new self($currencyCode,$currencyExchange);
    }

    public function getCurrencyCode(): CurrencyCode
    {
        return $this->currencyCode;
    }


    public function getCurrencyExchange(): CurrencyExchange
    {
        return $this->currencyExchange;
    }

}