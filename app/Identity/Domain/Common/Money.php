<?php

namespace App\Identity\Domain\Common;

final readonly class Money
{
    private function __construct(private int $amount)
    {
    }

    public static function fromInteger(int $amount): self
    {
        return new self($amount);
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function add(self $other): self
    {
        return new self($this->amount + $other->amount);
    }

    public function subtract(self $other): self
    {
        return new self($this->amount - $other->amount);
    }

    public function multiply(int $multiplier): self
    {
        return new self($this->amount * $multiplier);
    }

    public function equals(self $other): bool
    {
        return $this->amount === $other->amount;
    }

    public function gt(self $other): bool
    {
        return $this->amount > $other->amount;
    }

    public function lt(self $other): bool
    {
        return $this->amount < $other->amount;
    }

    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    public function isNegative(): bool
    {
        return $this->amount < 0;
    }
}
