<?php

namespace App\Identity\Domain\User\Event;

use App\Identity\Domain\Common\Money;
use App\Identity\Domain\User\UserId;

final readonly class UserBalanceDebited
{
    public function __construct(
        public UserId $id,
        public Money $amount,
        public Money $balance,
    ) {
    }
}
