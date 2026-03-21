<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Exceptions\CurrenciesMismatchException;
use App\Billing\Domain\Account\Exceptions\InsufficientFundsException;
use App\Billing\Domain\Account\Exceptions\InvalidMoneyException;
use App\Billing\Domain\Account\Exceptions\NegativeMultiplierException;
use App\Billing\Domain\Account\Money;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    public function testSuccessfullyCreateMoneyFromInteger(): void
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(1000, $usd);
        $otherMoney = Money::fromMinor(1000, $usd);

        $this->assertTrue($money->equals($otherMoney));
        $this->assertFalse($money->isZero());
    }

    public function testSuccessfullyCreateZeroMoney(): void
    {
        $usd = Currency::USD();
        $money = Money::zero($usd);

        $this->assertTrue($money->isZero());
    }

    public function testCreateNegativeMoney(): void
    {
        $this->expectException(InvalidMoneyException::class);

        $usd = Currency::USD();
        Money::fromMinor(-1000, $usd);
    }

    public function testSuccessfullyCreateMoneyFromString(): void
    {
        $usd = Currency::USD();
        $money = Money::fromString('100.12', $usd);
        $otherMoney = Money::fromMinor(10012, $usd);

        $this->assertTrue($money->equals($otherMoney));
        $this->assertFalse($money->isZero());
    }

    public function testSuccessfullyCreateMoneyFromStringWithComma(): void
    {
        $usd = Currency::USD();
        $money = Money::fromString('100,12', $usd);
        $otherMoney = Money::fromMinor(10012, $usd);

        $this->assertTrue($money->equals($otherMoney));
        $this->assertFalse($money->isZero());
    }

    public function testSuccessfullyCreateMoneyFromStringWithSpaces(): void
    {
        $usd = Currency::USD();
        $money = Money::fromString('1 000 12.55', $usd);
        $otherMoney = Money::fromMinor(10001255, $usd);

        $this->assertTrue($money->equals($otherMoney));
        $this->assertFalse($money->isZero());
    }

    public function testSuccessfullyCreateMoneyFromStringWithoutDecimals(): void
    {
        $usd = Currency::USD();
        $money = Money::fromString('1000', $usd);
        $otherMoney = Money::fromMinor(100000, $usd);

        $this->assertTrue($money->equals($otherMoney));
        $this->assertFalse($money->isZero());
    }

    public function testCreateMoneyFromWrongString(): void
    {
        $this->expectException(InvalidMoneyException::class);

        $usd = Currency::USD();
        Money::fromString('100XC12', $usd);
    }

    public function testMoneyGreatThenTrue(): void
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(1000, $usd);
        $otherMoney = Money::fromMinor(999, $usd);

        $this->assertTrue($money->gt($otherMoney));
    }

    public function testMoneyGreatThenFalse(): void
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(1000, $usd);
        $otherMoney = Money::fromMinor(999, $usd);

        $this->assertFalse($otherMoney->gt($money));
    }

    public function testMoneyGreatThenWithOtherCurrency(): void
    {
        $this->expectException(CurrenciesMismatchException::class);

        $money = Money::fromMinor(1000, Currency::USD());
        $otherMoney = Money::fromMinor(999, Currency::EUR());

        $money->gt($otherMoney);
    }

    public function testMoneyLessThenTrue(): void
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(999, $usd);
        $otherMoney = Money::fromMinor(1000, $usd);

        $this->assertTrue($money->lt($otherMoney));
    }

    public function testMoneyLessThenFalse(): void
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(999, $usd);
        $otherMoney = Money::fromMinor(1000, $usd);

        $this->assertFalse($otherMoney->lt($money));
    }

    public function testMoneyLessThenWithOtherCurrency(): void
    {
        $this->expectException(CurrenciesMismatchException::class);

        $money = Money::fromMinor(999, Currency::USD());
        $otherMoney = Money::fromMinor(1000, Currency::EUR());

        $money->lt($otherMoney);
    }

    public function testMoneyEqualsTrue(): void
    {
        $money = Money::fromMinor(1000, Currency::USD());
        $otherMoney = Money::fromMinor(1000, Currency::USD());

        $this->assertTrue($money->equals($otherMoney));
    }

    public function testMoneyEqualsWithOtherAmount(): void
    {
        $money = Money::fromMinor(1000, Currency::USD());
        $otherMoney = Money::fromMinor(2000, Currency::USD());

        $this->assertFalse($money->equals($otherMoney));
    }

    public function testMoneyEqualsWithOtherCurrency(): void
    {
        $money = Money::fromMinor(1000, Currency::USD());
        $otherMoney = Money::fromMinor(1000, Currency::EUR());

        $this->assertFalse($money->equals($otherMoney));
    }

    public function testAddMoney(): void
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(1000, $usd);

        $money = $money->add(Money::fromMinor(1000, $usd));

        $this->assertTrue($money->gt(Money::fromMinor(1999, $usd)));
        $this->assertTrue($money->lt(Money::fromMinor(2001, $usd)));
    }

    public function testAddMoneyWithOtherCurrency(): void
    {
        $this->expectException(CurrenciesMismatchException::class);

        $money = Money::fromMinor(1000, Currency::USD());

        $money->add(Money::fromMinor(1000, Currency::EUR()));
    }

    public function testSubtractMoney(): void
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(1000, $usd);

        $money = $money->subtract(Money::fromMinor(500, $usd));

        $this->assertTrue($money->gt(Money::fromMinor(499, $usd)));
        $this->assertTrue($money->lt(Money::fromMinor(501, $usd)));
    }

    public function testSubtractMoneyWithOtherCurrency(): void
    {
        $this->expectException(CurrenciesMismatchException::class);

        $money = Money::fromMinor(1000, Currency::USD());

        $money->subtract(Money::fromMinor(500, Currency::EUR()));
    }

    public function testSubtractUnsufficientMoney(): void
    {
        $this->expectException(InsufficientFundsException::class);

        $money = Money::fromMinor(1000, Currency::USD());

        $money->subtract(Money::fromMinor(1001, Currency::USD()));
    }

    public function testMultiplyMoney(): void
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(1000, $usd);

        $money = $money->multiply(3);

        $this->assertTrue($money->gt(Money::fromMinor(2999, $usd)));
        $this->assertTrue($money->lt(Money::fromMinor(3001, $usd)));
    }

    public function testMultiplyMoneyWithNegativeMultiplier(): void
    {
        $this->expectException(NegativeMultiplierException::class);

        $money = Money::fromMinor(1000, Currency::USD());

        $money->multiply(-1);
    }
}
