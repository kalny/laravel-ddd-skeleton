<?php

namespace App\Billing\Domain\Policies;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\UserId;

interface DefaultCurrencyPolicy
{
    public function determineFor(UserId $userId): Currency;
}
