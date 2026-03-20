<?php

namespace App\Billing\Domain\Account;

use App\Shared\Domain\ValueObjects\UuidId;

final readonly class UserId
{
    private UuidId $uuid;

    private function __construct(string $value)
    {
        $this->uuid = new UuidId($value);
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function equals(self $other): bool
    {
        return $this->uuid->getValue() === $other->uuid->getValue();
    }

    public function value(): string
    {
        return $this->uuid->getValue();
    }
}
