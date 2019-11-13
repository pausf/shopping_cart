<?php

namespace Cart\Currency\Domain;

use Cart\Currency\Domain\Exceptions\CurrencyCodeInvalidCodeException;

class CurrencyCode
{

    private $code;


    public function __construct($code)
    {
        $this->guardIsNotEmpty($code);
        $this->guardIfHaveCorrectFormat($code);
        $this->code = $code;
    }

    private function guardIfHaveCorrectFormat($code){

        if (!preg_match('/^[A-Z]{3}$/', $code)) {
             throw new CurrencyCodeInvalidCodeException();
        }
    }

    private function guardIsNotEmpty($code){

        if (empty($code)) {
            throw new \InvalidArgumentException();
        }
    }

    public function value(): string
    {
        return $this->code;
    }

    public function __toString()
    {
        return $this->value();
    }

}