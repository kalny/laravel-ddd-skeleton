<?php

namespace App\Identity\Application\UseCases\ChangeUserEmail;

final readonly class ChangeUserEmailCommand
{
    public function __construct(
        public string $id,
        public string $email,
    ) {
    }
}
