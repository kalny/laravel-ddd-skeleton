<?php

namespace App\Billing\Application\UseCases\OpenAccount;

final readonly class OpenAccountResult
{
    public function __construct(
        public string $id,
        public string $userId,
        public int $balance
    ) {
    }
}
