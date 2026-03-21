<?php

namespace App\Billing\Domain\Account;

use App\Billing\Domain\Account\Exceptions\InvalidCurrencyException;

final readonly class Currency
{
    private const DECIMAL_PLACES = [
        'USD' => 2,
        'UAH' => 2,
        'EUR' => 2
    ];

    private int $decimalPlaces;

    private function __construct(private string $code)
    {
        if (!in_array($code, array_keys(self::DECIMAL_PLACES), true)) {
            throw InvalidCurrencyException::withValue($code);
        }

        $this->decimalPlaces = self::DECIMAL_PLACES[$code];
    }

    public static function fromCode(string $code): self
    {
        return new self(mb_strtoupper(trim($code)));
    }

    public static function USD(): self
    {
        return self::fromCode('USD');
    }

    public static function UAH(): self
    {
        return self::fromCode('UAH');
    }

    public static function EUR(): self
    {
        return self::fromCode('EUR');
    }

    public function equals(self $other): bool
    {
        return $this->code === $other->code;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function decimalPlaces(): int
    {
        return $this->decimalPlaces;
    }
}
