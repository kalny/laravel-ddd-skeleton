<?php

namespace Tests\Unit\Identity\Domain\Common;

use App\Identity\Domain\Common\Money;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    public function testSuccessfullyCreateMoneyFromInteger(): void
    {
        $money = Money::fromInteger(1000);
        $otherMoney = Money::fromInteger(1000);

        $this->assertTrue($money->equals($otherMoney));
        $this->assertFalse($money->isZero());
    }

    public function testSuccessfullyCreateZeroMoney(): void
    {
        $money = Money::zero();

        $this->assertTrue($money->isZero());
    }

    public function testSuccessfullyCreateNegativeMoney(): void
    {
        $money = Money::fromInteger(-1000);

        $this->assertTrue($money->isNegative());
    }

    public function testMoneyGreatThenTrue(): void
    {
        $money = Money::fromInteger(1000);
        $otherMoney = Money::fromInteger(999);

        $this->assertTrue($money->gt($otherMoney));
    }

    public function testMoneyGreatThenFalse(): void
    {
        $money = Money::fromInteger(1000);
        $otherMoney = Money::fromInteger(999);

        $this->assertFalse($otherMoney->gt($money));
    }

    public function testMoneyLessThenTrue(): void
    {
        $money = Money::fromInteger(999);
        $otherMoney = Money::fromInteger(1000);

        $this->assertTrue($money->lt($otherMoney));
    }

    public function testMoneyLessThenFalse(): void
    {
        $money = Money::fromInteger(999);
        $otherMoney = Money::fromInteger(1000);

        $this->assertFalse($otherMoney->lt($money));
    }

    public function testAddMoney()
    {
        $money = Money::fromInteger(1000);

        $money = $money->add(Money::fromInteger(1000));

        $this->assertTrue($money->gt(Money::fromInteger(1999)));
        $this->assertTrue($money->lt(Money::fromInteger(2001)));
    }

    public function testSubtractMoney()
    {
        $money = Money::fromInteger(1000);

        $money = $money->subtract(Money::fromInteger(500));

        $this->assertTrue($money->gt(Money::fromInteger(499)));
        $this->assertTrue($money->lt(Money::fromInteger(501)));
    }

    public function testMultiplyMoney()
    {
        $money = Money::fromInteger(1000);

        $money = $money->multiply(3);

        $this->assertTrue($money->gt(Money::fromInteger(2999)));
        $this->assertTrue($money->lt(Money::fromInteger(3001)));
    }
}
