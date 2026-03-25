<?php

namespace App\Identity\Application\UseCases\Queries\GetUser;

use App\Shared\Application\Bus\Query;

final readonly class GetUserQuery implements Query
{
    public function __construct(
        public string $id,
    ) {
    }
}
