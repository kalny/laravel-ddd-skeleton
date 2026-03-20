<?php

namespace App\Identity\Application\UseCases\LoginUser;

final readonly class LoginUserResult
{
    public function __construct(
        public string $id,
        public string $email,
        public string $token,
    ) {
    }
}
