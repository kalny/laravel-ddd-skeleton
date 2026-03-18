<?php

namespace App\Domain\User\Events;

use App\Domain\User\UserId;
use App\Domain\User\UserName;

final readonly class UserNameChanged
{
    public function __construct(
        public UserId $id,
        public UserName $newName
    ) {
    }
}
