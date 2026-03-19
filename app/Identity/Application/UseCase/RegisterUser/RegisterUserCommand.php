<?php

namespace App\Identity\Application\UseCase\RegisterUser;

final readonly class RegisterUserCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {
    }
}
