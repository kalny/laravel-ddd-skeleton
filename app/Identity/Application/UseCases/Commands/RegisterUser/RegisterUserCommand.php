<?php

namespace App\Identity\Application\UseCases\Commands\RegisterUser;

final readonly class RegisterUserCommand
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
