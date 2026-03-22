<?php

namespace App\Identity\Application\UseCases\Commands\ChangeUserPassword;

final readonly class ChangeUserPasswordCommand
{
    public function __construct(
        public string $id,
        public string $password,
    ) {
    }
}
