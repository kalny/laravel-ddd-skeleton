<?php

namespace App\Application\UseCase\ChangeUserPassword;

final readonly class ChangeUserPasswordCommand
{
    public function __construct(
        public string $id,
        public string $password,
    ) {
    }
}
