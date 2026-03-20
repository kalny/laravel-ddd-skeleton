<?php

namespace App\Identity\Infrastructure\IntegrationEvents;

use App\Identity\Domain\Common\Email;
use App\Identity\Domain\User\UserId;
use App\Identity\Domain\User\UserName;

final readonly class UserRegisteredIntegrationEvent
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email
    ) {
    }
}
