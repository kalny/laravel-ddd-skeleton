<?php

namespace App\Identity\Domain\User;

use App\Shared\Domain\Exception\InvalidArgumentException;

final readonly class PlainPassword
{
    private function __construct(private string $plain)
    {
        if (empty($plain)) {
            throw new InvalidArgumentException('Password cannot be empty');
        }

        if (strlen($plain) < 8) {
            throw new InvalidArgumentException('Password cannot be less than 8 characters');
        }
    }

    public static function fromString(string $plain): self
    {
        return new self(trim($plain));
    }

    public function getValue(): string
    {
        return $this->plain;
    }
}
