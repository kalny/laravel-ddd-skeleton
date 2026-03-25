<?php

namespace App\Billing\Application\UseCases\Commands\OpenAccount;

use App\Shared\Application\Bus\Command;

final readonly class OpenAccountCommand implements Command
{
    public function __construct(
        public string $userId,
        public int $balance = 0
    ) {
    }
}
