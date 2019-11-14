<?php


namespace Cart\Shared\Domain\ValueObject;

use Cart\Shared\Domain\Exception\UuidNotValidException;
use Cart\Shared\Domain\Exception\ValueIsEmptyException;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid as Ramsey;

class Uuid
{

    private $value;

    public function __construct(string $value)
    {
        $this->guardIsNotEmpty($value);
        $this->guardIsValidUuid($value);
        $this->value = $value;
    }


    private function guardIsValidUuid($value): void
    {
        if (!Ramsey::isValid($value)) {
            throw new UuidNotValidException($value . ' is not a valid value');
        }
    }

    private function guardIsNotEmpty($value)
    {
        if (empty($value)) {
            throw new ValueIsEmptyException($value . 'cannot be empty');
        }
    }

    public static function generate(): self
    {
        return new self(Ramsey::uuid4()->toString());
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }
}