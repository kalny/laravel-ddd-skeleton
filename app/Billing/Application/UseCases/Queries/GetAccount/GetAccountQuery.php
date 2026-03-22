<?php

namespace App\Billing\Application\UseCases\Queries\GetAccount;

final readonly class GetAccountQuery
{
    public function __construct(public string $userId)
    {
    }
}
