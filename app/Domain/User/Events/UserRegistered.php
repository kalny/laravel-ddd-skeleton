<?php

namespace App\Domain\User\Events;

use App\Domain\Common\Email;
use App\Domain\User\UserId;
use App\Domain\User\UserName;

final readonly class UserRegistered
{
    public function __construct(
        public UserId $id,
        public UserName $name,
        public Email $email
    ) {
    }
}
