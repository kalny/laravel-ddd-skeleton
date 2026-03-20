<?php

namespace App\Identity\Infrastructure\DomainEventListeners;

use App\Identity\Domain\User\Events\UserRegistered;
use App\Identity\Infrastructure\IntegrationEvents\UserRegisteredIntegrationEvent;
use Illuminate\Support\Facades\Log;

class LogUserRegisteredEvent
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
        Log::channel('events')->info('UserRegistered', [
            'user_id' => $event->id->value()
        ]);

        event(new UserRegisteredIntegrationEvent(
            id: $event->id->value(),
            email: $event->email->getValue()
        ));
    }
}
