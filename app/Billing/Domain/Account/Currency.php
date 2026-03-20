<?php

namespace App\Billing\Domain\Account;

use App\Billing\Domain\Account\Exceptions\InvalidCurrencyException;

final readonly class Currency
{
    private const AVAILABLE_CODES = ['USD', 'UAH', 'EUR'];

    private function __construct(private string $code)
    {
        if (!in_array($code, self::AVAILABLE_CODES, true)) {
            throw InvalidCurrencyException::withValue($code);
        }
    }

    public static function fromString(string $code): self
    {
        return new self(mb_strtoupper(trim($code)));
    }

    public static function USD(): self
    {
        return new self('USD');
    }

    public static function UAH(): self
    {
        return new self('UAH');
    }

    public static function EUR(): self
    {
        return new self('EUR');
    }

    public function equals(self $other): bool
    {
        return $this->code === $other->code;
    }
}
