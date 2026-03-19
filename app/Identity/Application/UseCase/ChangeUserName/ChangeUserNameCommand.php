<?php

namespace App\Identity\Application\UseCase\ChangeUserName;

final readonly class ChangeUserNameCommand
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
