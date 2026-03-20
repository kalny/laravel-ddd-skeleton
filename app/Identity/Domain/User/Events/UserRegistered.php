<?php

namespace App\Identity\Domain\User\Events;

use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\UserId;

final readonly class UserRegistered
{
    public function __construct(
        public UserId $id,
        public Email $email
    ) {
    }
}
