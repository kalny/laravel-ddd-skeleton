<?php

namespace App\Identity\Domain\User\Events;

use App\Identity\Domain\Common\Email;
use App\Identity\Domain\User\UserId;

final readonly class UserEmailChanged
{
    public function __construct(
        public UserId $id,
        public Email $email
    ) {
    }
}
