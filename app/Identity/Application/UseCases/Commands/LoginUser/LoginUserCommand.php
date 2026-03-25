<?php

namespace App\Identity\Application\UseCases\Commands\LoginUser;

use App\Shared\Application\Bus\Command;

final readonly class LoginUserCommand implements Command
{
    public function __construct(
        public string $email,
        public string $password
    ) {
    }
}
