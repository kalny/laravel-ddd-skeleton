<?php

namespace App\Application\UseCase\ChangeUserName;

final readonly class ChangeUserNameCommand
{
    public function __construct(
        public string $id,
        public string $name,
    ) {
    }
}
