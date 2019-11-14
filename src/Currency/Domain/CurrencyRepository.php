<?php


namespace Cart\Currency\Domain;

interface CurrencyRepository
{
    public function get(CurrencyCode $code): string;
}