<?php

namespace App\Billing\Application\UseCases\Commands\Deposit;

use App\Shared\Application\Bus\Command;

final readonly class DepositCommand implements Command
{
    public function __construct(
        public string $userId,
        public string $amount,
        public string $currency
    ) {
    }
}
