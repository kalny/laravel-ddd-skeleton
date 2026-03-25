<?php

namespace App\Identity\Application\UseCases\Commands\LogoutUser;

use App\Shared\Application\Bus\Command;

final readonly class LogoutUserCommand implements Command
{
    public function __construct(
        public string $id
    ) {
    }
}
