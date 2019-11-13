<?php


namespace Cart\Shared\Domain\ValueObject;


use Cart\Shared\Domain\Exception\InvalidNumberArgumentException;
use Cart\Shared\Domain\Exception\NumberNotNegativeOrZero;
use Cart\Shared\Domain\Exception\ValueIsEmptyException;
use InvalidArgumentException;

class IntNumber
{
    private $value;

    public function __construct($value)
    {
        $this->guardIsNotEmpty($value);
        $this->guardIsValidInNumber($value);
        $this->guardIsNotNegativeOrZero($value);
        $this->value = $value;
    }


    private function guardIsValidInNumber($value): void
    {
        if (!is_int($value)) {
            throw new InvalidNumberArgumentException($value.' is not a valid value');
        }
    }

    private function guardIsNotNegativeOrZero($value)
    {
        if ($value <= 0) {
            throw new NumberNotNegativeOrZero($value.' is not a valid value');
        }
    }

    private function guardIsNotEmpty($value){

        if (!isset($value)) {
            throw new ValueIsEmptyException($value.' cannot be empty');
        }
    }

    public function value(): string
    {
        return $this->value;
    }
}