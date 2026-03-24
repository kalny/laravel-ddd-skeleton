<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Exceptions\InvalidCurrencyException;
use PHPUnit\Framework\TestCase;

class CurrencyTest extends TestCase
{
    public function testSuccessfullyCreateCurrency(): void
    {
        $usd = Currency::USD();

        $this->assertTrue($usd->equals(Currency::USD()));
    }

    public function testSuccessfullyCreateCurrencyFromStringAndTransformToUpperCase(): void
    {
        $usd = Currency::fromCode('usd');

        $this->assertTrue($usd->equals(Currency::USD()));
    }

    public function testSuccessfullyCreateCurrencyFromStringAndTrim(): void
    {
        $usd = Currency::fromCode(' USD ');

        $this->assertTrue($usd->equals(Currency::USD()));
    }

    public function testCreateCurrencyFromWrongString(): void
    {
        $this->expectException(InvalidCurrencyException::class);
        Currency::fromCode('ABC');
    }

    public function testCurrencyNotEquals(): void
    {
        $usd = Currency::USD();
        $eur = Currency::EUR();

        $this->assertFalse($usd->equals($eur));
    }
}
