<?php

namespace App\Identity\Application\UseCases\RegisterUser;

final readonly class RegisterUserResult
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email
    ) {
    }
}
