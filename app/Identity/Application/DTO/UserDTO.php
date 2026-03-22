<?php

namespace App\Identity\Application\DTO;

final readonly class UserDTO
{
    public function __construct(
        public string $id,
        public string $email
    ) {
    }
}
