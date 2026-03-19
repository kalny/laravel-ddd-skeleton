<?php

namespace App\Identity\Domain\User\Event;

use App\Identity\Domain\User\UserId;
use App\Identity\Domain\User\UserName;

final readonly class UserNameChanged
{
    public function __construct(
        public UserId $id,
        public UserName $newName
    ) {
    }
}
