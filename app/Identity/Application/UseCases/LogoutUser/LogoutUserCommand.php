<?php

namespace App\Identity\Application\UseCases\LogoutUser;

final readonly class LogoutUserCommand
{
    public function __construct(
        public string $id
    ) {
    }
}
