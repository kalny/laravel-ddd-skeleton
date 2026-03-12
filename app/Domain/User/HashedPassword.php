<?php

namespace App\Domain\User;

use App\Domain\Common\Exceptions\InvalidArgumentException;

final readonly class HashedPassword
{
    private function __construct(private string $hash)
    {
        if (empty($hash)) {
            throw new InvalidArgumentException('hash cannot be empty');
        }
    }

    public static function fromHash(string $hash): self
    {
        return new self(trim($hash));
    }

    public function getValue(): string
    {
        return $this->hash;
    }
}
