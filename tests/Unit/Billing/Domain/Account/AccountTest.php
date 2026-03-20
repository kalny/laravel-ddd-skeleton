<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\Account;
use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Events\AccountBalanceCredited;
use App\Billing\Domain\Account\Events\AccountBalanceDebited;
use App\Billing\Domain\Account\Events\AccountOpened;
use App\Billing\Domain\Account\Exceptions\InsufficientFundsException;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\UserId;
use Illuminate\Support\Str;
use Tests\TestCase;

class AccountTest extends TestCase
{
    public function testSuccessfullyOpenAccount(): void
    {
        $accountId = AccountId::fromString(Str::uuid()->toString());
        $userId = UserId::fromString(Str::uuid()->toString());

        $account = Account::open($accountId, $userId);

        $this->assertTrue($account->id()->equals($accountId));

        $events = $account->releaseEvents();

        $this->assertCount(1, $events);

        $this->assertEquals(new AccountOpened(
            $accountId,
            $userId,
            Money::zero(),
        ), $events[0]);
    }

    public function testSuccessfullyOpenAccountWithBalance(): void
    {
        $accountId = AccountId::fromString(Str::uuid()->toString());
        $userId = UserId::fromString(Str::uuid()->toString());

        $account = Account::openWithBalance($accountId, $userId, Money::fromInteger(500));

        $this->assertTrue($account->id()->equals($accountId));

        $events = $account->releaseEvents();

        $this->assertCount(1, $events);

        $this->assertEquals(new AccountOpened(
            $accountId,
            $userId,
            Money::fromInteger(500),
        ), $events[0]);
    }

    public function testSuccessfullyCreditBalance(): void
    {
        $accountId = AccountId::fromString(Str::uuid()->toString());
        $userId = UserId::fromString(Str::uuid()->toString());

        $account = Account::open($accountId, $userId);

        $account->credit(Money::fromInteger(1000));

        $events = $account->releaseEvents();

        $this->assertCount(2, $events);

        $this->assertEquals(new AccountBalanceCredited(
            $accountId,
            Money::fromInteger(1000),
            Money::fromInteger(1000),
        ), $events[1]);
    }

    public function testSuccessfullyDebitBalance(): void
    {
        $accountId = AccountId::fromString(Str::uuid()->toString());
        $userId = UserId::fromString(Str::uuid()->toString());

        $account = Account::open($accountId, $userId);

        $account->credit(Money::fromInteger(1000));
        $account->debit(Money::fromInteger(1000));

        $events = $account->releaseEvents();

        $this->assertCount(3, $events);

        $this->assertEquals(new AccountBalanceCredited(
            $accountId,
            Money::fromInteger(1000),
            Money::fromInteger(1000),
        ), $events[1]);

        $this->assertEquals(new AccountBalanceDebited(
            $accountId,
            Money::fromInteger(1000),
            Money::fromInteger(0),
        ), $events[2]);
    }

    public function testDebitUnsufficientFunds(): void
    {
        $this->expectException(InsufficientFundsException::class);

        $accountId = AccountId::fromString(Str::uuid()->toString());
        $userId = UserId::fromString(Str::uuid()->toString());

        $account = Account::open($accountId, $userId);

        $account->debit(Money::fromInteger(1000));
    }
}
