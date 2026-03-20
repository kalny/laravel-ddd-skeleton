<?php

namespace App\Identity\Application\UseCases\ChangeUserPassword;

final readonly class ChangeUserPasswordCommand
{
    public function __construct(
        public string $id,
        public string $password,
    ) {
    }
}
