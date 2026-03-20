<?php

namespace App\Billing\Domain\Services;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\UserId;

interface CurrencyPolicy
{
    public function defaultForUser(UserId $userId): Currency;
}
