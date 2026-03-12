<?php

namespace App\Domain\User\Events;

use App\Domain\Common\Money;
use App\Domain\User\UserId;

final readonly class UserBalanceDebited
{
    public function __construct(
        public UserId $id,
        public Money $amount,
        public Money $balance,
    ) {
    }
}
