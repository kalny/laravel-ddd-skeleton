<?php

namespace App\Billing\Domain\Account\Events;

use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\UserId;

final readonly class AccountOpened
{
    public function __construct(
        public AccountId $id,
        public UserId $userId,
        public Money $balance,
    ) {
    }
}
