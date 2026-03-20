<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\Currency;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    public function testSuccessfullyCreateCurrency(): void
    {
        $usd = Currency::USD();

        $this->assertTrue($usd->equals(Currency::USD()));
    }

    public function testSuccessfullyCreateCurrencyFromStringAndTransformToUpperCase(): void
    {
        $usd = Currency::fromString('usd');

        $this->assertTrue($usd->equals(Currency::USD()));
    }

    public function testSuccessfullyCreateCurrencyFromStringAndTrim(): void
    {
        $usd = Currency::fromString(' USD ');

        $this->assertTrue($usd->equals(Currency::USD()));
    }

    public function testCurrencyNotEquals(): void
    {
        $usd = Currency::USD();
        $eur = Currency::EUR();

        $this->assertFalse($usd->equals($eur));
    }
}
