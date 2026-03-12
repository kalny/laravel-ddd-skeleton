<?php

namespace App\Application\UseCase\RegisterUser;

final readonly class RegisterUserResult
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email
    ) {
    }
}
