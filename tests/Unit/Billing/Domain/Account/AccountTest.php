<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\Account;
use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Events\AccountBalanceCredited;
use App\Billing\Domain\Account\Events\AccountBalanceDebited;
use App\Billing\Domain\Account\Events\AccountOpened;
use App\Billing\Domain\Account\Exceptions\InsufficientFundsException;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\UserId;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    public function testSuccessfullyOpenAccount(): void
    {
        $accountId = AccountId::fromString('account-id');
        $userId = UserId::fromString('user-id');

        $usd = Currency::USD();

        $account = Account::open($accountId, $userId, $usd);

        $this->assertTrue($account->id()->equals($accountId));

        $events = $account->releaseEvents();

        $this->assertCount(1, $events);

        $this->assertEquals(new AccountOpened(
            $accountId,
            $userId,
            Money::zero($usd),
        ), $events[0]);
    }

    public function testSuccessfullyOpenAccountWithBalance(): void
    {
        $accountId = AccountId::fromString('account-id');
        $userId = UserId::fromString('user-id');

        $usd = Currency::USD();
        $account = Account::openWithBalance($accountId, $userId, Money::fromMinor(500, $usd));

        $this->assertTrue($account->id()->equals($accountId));

        $events = $account->releaseEvents();

        $this->assertCount(1, $events);

        $this->assertEquals(new AccountOpened(
            $accountId,
            $userId,
            Money::fromMinor(500, $usd),
        ), $events[0]);
    }

    public function testSuccessfullyCreditBalance(): void
    {
        $accountId = AccountId::fromString('account-id');
        $userId = UserId::fromString('user-id');

        $usd = Currency::USD();

        $account = Account::open($accountId, $userId, $usd);

        $account->credit(Money::fromMinor(1000, $usd));

        $events = $account->releaseEvents();

        $this->assertCount(2, $events);

        $this->assertEquals(new AccountBalanceCredited(
            $accountId,
            Money::fromMinor(1000, $usd),
            Money::fromMinor(1000, $usd),
        ), $events[1]);
    }

    public function testSuccessfullyDebitBalance(): void
    {
        $accountId = AccountId::fromString('account-id');
        $userId = UserId::fromString('user-id');

        $usd = Currency::USD();

        $account = Account::open($accountId, $userId, $usd);

        $account->credit(Money::fromMinor(1000, $usd));
        $account->debit(Money::fromMinor(1000, $usd));

        $events = $account->releaseEvents();

        $this->assertCount(3, $events);

        $this->assertEquals(new AccountBalanceCredited(
            $accountId,
            Money::fromMinor(1000, $usd),
            Money::fromMinor(1000, $usd),
        ), $events[1]);

        $this->assertEquals(new AccountBalanceDebited(
            $accountId,
            Money::fromMinor(1000, $usd),
            Money::fromMinor(0, $usd),
        ), $events[2]);
    }

    public function testDebitUnsufficientFunds(): void
    {
        $this->expectException(InsufficientFundsException::class);

        $accountId = AccountId::fromString('account-id');
        $userId = UserId::fromString('user-id');

        $usd = Currency::USD();

        $account = Account::open($accountId, $userId, $usd);

        $account->debit(Money::fromMinor(1000, $usd));
    }

    public function testAccountBelongsToUserTrue(): void
    {
        $accountId = AccountId::fromString('account-id');
        $userId = UserId::fromString('user-id');

        $usd = Currency::USD();

        $account = Account::open($accountId, $userId, $usd);

        $this->assertTrue($account->belongsTo($userId));
    }

    public function testAccountBelongsToUserFalse(): void
    {
        $accountId = AccountId::fromString('account-id');
        $userId = UserId::fromString('user-id');
        $otherUserId = UserId::fromString('other-user-id');

        $usd = Currency::USD();

        $account = Account::open($accountId, $userId, $usd);

        $this->assertFalse($account->belongsTo($otherUserId));
    }
}
