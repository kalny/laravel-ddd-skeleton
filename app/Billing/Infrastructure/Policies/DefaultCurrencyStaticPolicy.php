<?php

namespace App\Billing\Infrastructure\Policies;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\UserId;
use App\Billing\Domain\Policies\DefaultCurrencyPolicy;

class DefaultCurrencyStaticPolicy implements DefaultCurrencyPolicy
{
    public function determineFor(UserId $userId): Currency
    {
        return Currency::USD();
    }
}
