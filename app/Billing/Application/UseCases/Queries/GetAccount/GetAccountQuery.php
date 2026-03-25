<?php

namespace App\Billing\Application\UseCases\Queries\GetAccount;

use App\Shared\Application\Bus\Query;

final readonly class GetAccountQuery implements Query
{
    public function __construct(public string $userId)
    {
    }
}
