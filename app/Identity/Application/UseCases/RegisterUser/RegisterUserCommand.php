<?php

namespace App\Identity\Application\UseCases\RegisterUser;

final readonly class RegisterUserCommand
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password
    ) {
    }
}
