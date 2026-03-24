<?php

namespace App\Billing\Domain\Account;

use App\Billing\Domain\Account\Events\AccountBalanceCredited;
use App\Billing\Domain\Account\Events\AccountBalanceDebited;
use App\Billing\Domain\Account\Events\AccountOpened;
use App\Billing\Domain\Account\Exceptions\InsufficientFundsException;
use App\Shared\Domain\AggregateRoot;

class Account extends AggregateRoot
{
    private function __construct(
        private readonly AccountId $id,
        private readonly UserId $userId,
        private Money $balance,
    ) {
    }

    public static function open(
        AccountId $id,
        UserId $userId,
        Currency $currency,
    ): self {
        $balance = Money::zero($currency);

        $account = new self($id, $userId, $balance);

        $account->record(new AccountOpened($id, $userId, $balance));

        return $account;
    }

    public static function openWithBalance(
        AccountId $id,
        UserId $userId,
        Money $balance
    ): self {
        $account = new self($id, $userId, $balance);

        $account->record(new AccountOpened($id, $userId, $balance));

        return $account;
    }

    public function id(): AccountId
    {
        return $this->id;
    }

    public function balance(): Money
    {
        return $this->balance;
    }

    public function debit(Money $money): void
    {
        if ($this->balance->lt($money)) {
            throw new InsufficientFundsException('Insufficient funds on the balance');
        }
        $this->balance = $this->balance->subtract($money);

        $this->record(new AccountBalanceDebited($this->id, $money, $this->balance));
    }

    public function credit(Money $money): void
    {
        $this->balance = $this->balance->add($money);

        $this->record(new AccountBalanceCredited($this->id, $money, $this->balance));
    }

    public function belongsTo(UserId $userId): bool
    {
        return $this->userId->equals($userId);
    }
}
