<?php

namespace App\Identity\Infrastructure\IntegrationEvents;

final readonly class UserRegisteredIntegrationEvent
{
    public function __construct(
        public string $id,
        public string $email
    ) {
    }
}
