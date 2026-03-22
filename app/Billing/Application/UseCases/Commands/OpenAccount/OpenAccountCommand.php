<?php

namespace App\Billing\Application\UseCases\Commands\OpenAccount;

final readonly class OpenAccountCommand
{
    public function __construct(
        public string $userId,
        public int $balance = 0
    ) {
    }
}
