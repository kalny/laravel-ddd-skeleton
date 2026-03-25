<?php

namespace App\Identity\Application\UseCases\Commands\ChangeUserPassword;

use App\Shared\Application\Bus\Command;

final readonly class ChangeUserPasswordCommand implements Command
{
    public function __construct(
        public string $id,
        public string $password,
    ) {
    }
}
