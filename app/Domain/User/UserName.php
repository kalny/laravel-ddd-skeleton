<?php

namespace App\Domain\User;

use App\Domain\Common\Exceptions\InvalidArgumentException;

final readonly class UserName
{
    private function __construct(private string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('User name cannot be empty');
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
