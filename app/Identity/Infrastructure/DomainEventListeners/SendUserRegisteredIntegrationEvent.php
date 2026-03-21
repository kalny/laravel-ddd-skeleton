<?php

namespace App\Identity\Infrastructure\DomainEventListeners;

use App\Identity\Domain\User\Events\UserRegistered;
use App\Identity\Infrastructure\IntegrationEvents\UserRegisteredIntegrationEvent;
use Illuminate\Support\Facades\Log;

class SendUserRegisteredIntegrationEvent
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        event(new UserRegisteredIntegrationEvent(
            id: $event->id->value(),
            email: $event->email->value()
        ));
    }
}
