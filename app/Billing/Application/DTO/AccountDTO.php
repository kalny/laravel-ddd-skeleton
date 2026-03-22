<?php

namespace App\Billing\Application\DTO;

final readonly class AccountDTO
{
    public function __construct(
        public string $id,
        public string $userId,
        public int $balance
    ) {
    }
}
