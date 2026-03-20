<?php

namespace App\Billing\Application\UseCases\OpenAccount;

final readonly class OpenAccountCommand
{
    public function __construct(
        public string $userId,
        public int $balance = 0
    ) {
    }
}
