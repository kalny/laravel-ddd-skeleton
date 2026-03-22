<?php

namespace App\Identity\Application\UseCases\Commands\LoginUser;

final readonly class LoginUserCommand
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
