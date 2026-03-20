<?php

namespace App\Billing\Domain\Account;

use App\Billing\Domain\Account\Exceptions\CurrenciesMismatchException;
use App\Billing\Domain\Account\Exceptions\InsufficientFundsException;
use App\Billing\Domain\Account\Exceptions\InvalidMoneyException;

final readonly class Money
{
    private function __construct(private int $amount, private Currency $currency)
    {
    }

    public static function fromMinor(int $amount, Currency $currency): self
    {
        if ($amount < 0) {
            throw new InvalidMoneyException('Amount should not be less than zero');
        }

        return new self($amount, $currency);
    }

    public static function zero(Currency $currency): self
    {
        return new self(0, $currency);
    }

    public function add(self $other): self
    {
        $this->assertSameCurrency($other->currency);

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(self $other): self
    {
        if ($this->lt($other)) {
            throw new InsufficientFundsException();
        }

        return new self($this->amount - $other->amount, $this->currency);
    }

    public function multiply(int $multiplier): self
    {
        return new self($this->amount * $multiplier, $this->currency);
    }

    public function equals(self $other): bool
    {
        $this->assertSameCurrency($other->currency);

        return $this->amount === $other->amount;
    }

    public function gt(self $other): bool
    {
        $this->assertSameCurrency($other->currency);

        return $this->amount > $other->amount;
    }

    public function lt(self $other): bool
    {
        $this->assertSameCurrency($other->currency);

        return $this->amount < $other->amount;
    }

    public function isZero(): bool
    {
        return $this->amount === 0;
    }

    private function assertSameCurrency(Currency $currency): void
    {
        if (!$this->currency->equals($currency)) {
            throw new CurrenciesMismatchException();
        }
    }
}
