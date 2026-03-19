<?php

namespace App\Identity\Domain\User\Event;

use App\Identity\Domain\User\UserId;

final readonly class UserPasswordChanged
{
    public function __construct(
        public UserId $id
    ) {
    }
}
