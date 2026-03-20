<?php

namespace App\Billing\Infrastructure\Services;

use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\UserId;
use App\Billing\Domain\Services\CurrencyPolicy;

class DefaultCurrencyPolicy implements CurrencyPolicy
{
    public function defaultForUser(UserId $userId): Currency
    {
        return Currency::USD();
    }
}
