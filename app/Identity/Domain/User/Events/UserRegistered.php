<?php

namespace App\Identity\Domain\User\Events;

use App\Identity\Domain\Common\Email;
use App\Identity\Domain\User\UserId;
use App\Identity\Domain\User\UserName;

final readonly class UserRegistered
{
    public function __construct(
        public UserId $id,
        public UserName $name,
        public Email $email
    ) {
    }
}
