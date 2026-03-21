<?php

namespace App\Identity\Domain\User;

use App\Shared\Domain\Exceptions\InvalidArgumentException;
use App\Identity\Domain\User\Exceptions\InvalidEmailException;

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

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
