<?php

namespace App\Identity\Application\UseCases\Commands\ChangeUserEmail;

use App\Shared\Application\Bus\Command;

final readonly class ChangeUserEmailCommand implements Command
{
    public function __construct(
        public string $id,
        public string $email,
    ) {
    }
}
