<?php

namespace App\Identity\Application\UseCases\Queries\GetUser;

final readonly class GetUserQuery
{
    public function __construct(
        public string $id,
    ) {
    }
}
