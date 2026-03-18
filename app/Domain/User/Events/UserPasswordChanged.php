<?php

namespace App\Domain\User\Events;

use App\Domain\User\UserId;

final readonly class UserPasswordChanged
{
    public function __construct(
        public UserId $id
    ) {
    }
}
