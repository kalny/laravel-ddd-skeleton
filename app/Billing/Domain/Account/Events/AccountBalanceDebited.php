<?php

namespace App\Billing\Domain\Account\Events;

use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Money;

final readonly class AccountBalanceDebited
{
    public function __construct(
        public AccountId $id,
        public Money $amount,
        public Money $balance,
    ) {
    }
}
