<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Exceptions\InvalidMoneyException;
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

    public function testSuccessfullyCreateNegativeMoney(): void
    {
        $this->expectException(InvalidMoneyException::class);

        $usd = Currency::USD();
        Money::fromMinor(-1000, $usd);
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

    public function testAddMoney()
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(1000, $usd);

        $money = $money->add(Money::fromMinor(1000, $usd));

        $this->assertTrue($money->gt(Money::fromMinor(1999, $usd)));
        $this->assertTrue($money->lt(Money::fromMinor(2001, $usd)));
    }

    public function testSubtractMoney()
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(1000, $usd);

        $money = $money->subtract(Money::fromMinor(500, $usd));

        $this->assertTrue($money->gt(Money::fromMinor(499, $usd)));
        $this->assertTrue($money->lt(Money::fromMinor(501, $usd)));
    }

    public function testMultiplyMoney()
    {
        $usd = Currency::USD();

        $money = Money::fromMinor(1000, $usd);

        $money = $money->multiply(3);

        $this->assertTrue($money->gt(Money::fromMinor(2999, $usd)));
        $this->assertTrue($money->lt(Money::fromMinor(3001, $usd)));
    }
}
