<?php

namespace App\Identity\Domain\Common;

use App\Shared\Domain\Exception\InvalidArgumentException;
use App\Identity\Domain\Common\Exception\InvalidEmailException;

final readonly class Email
{
    private function __construct(private string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Email cannot be empty');
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailException::withValue($value);
        }
    }

    public static function fromString(string $value): self
    {
        return new self(mb_strtolower(trim($value)));
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
