<?php

namespace App\Identity\Infrastructure\DomainEventListeners;

use App\Identity\Domain\User\Event\UserRegistered;
use App\Identity\Infrastructure\IntegrationEvent\UserRegisteredIntegrationEvent;
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
            name: $event->name->getValue(),
            email: $event->email->getValue()
        ));
    }
}
