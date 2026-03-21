<?php

namespace App\Billing\Application\UseCases\Deposit;

final readonly class DepositCommand
{
    public function __construct(
        public string $userId,
        public string $amount,
        public string $currency
    ) {
    }
}
