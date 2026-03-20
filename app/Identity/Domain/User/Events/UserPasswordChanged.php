<?php

namespace App\Identity\Domain\User\Events;

use App\Identity\Domain\User\UserId;

final readonly class UserPasswordChanged
{
    public function __construct(
        public UserId $id
    ) {
    }
}
