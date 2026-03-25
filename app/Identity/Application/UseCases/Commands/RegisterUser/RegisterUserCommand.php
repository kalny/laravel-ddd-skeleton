<?php

namespace App\Identity\Application\UseCases\Commands\RegisterUser;

use App\Shared\Application\Bus\Command;

final readonly class RegisterUserCommand implements Command
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
