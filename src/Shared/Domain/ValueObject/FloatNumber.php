<?php


namespace Cart\Shared\Domain\ValueObject;

use Cart\Shared\Domain\Exception\InvalidNumberArgumentException;
use Cart\Shared\Domain\Exception\NumberNotNegativeOrZero;
use InvalidArgumentException;

class FloatNumber
{

    private const DECIMALS = 2;

    private $value;

    public function __construct($value)
    {
        $this->guardIsNotEmpty($value);
        $this->guardIsValidNumber($value);
        $this->guardIsNotNegativeOrZero($value);
        $this->value = $value;
    }


    private function guardIsValidNumber($value): void
    {
        if (!is_numeric($value)) {
            throw new InvalidNumberArgumentException($value.' is not a valid value');
        }
    }

    private function guardIsNotNegativeOrZero($value)
    {
        if ($value <= 0) {
            throw new NumberNotNegativeOrZero($value.' is not a valid value');
        }
    }

    public function value(): string
    {
        return number_format($this->value, self::DECIMALS, '.', ' ');
    }

    private function guardIsNotEmpty($value){

        if (empty($value)) {
            throw new InvalidArgumentException();
        }
    }
}