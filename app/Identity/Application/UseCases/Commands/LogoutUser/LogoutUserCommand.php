<?php

namespace App\Identity\Application\UseCases\Commands\LogoutUser;

final readonly class LogoutUserCommand
{
    public function __construct(
        public string $id
    ) {
    }
}
