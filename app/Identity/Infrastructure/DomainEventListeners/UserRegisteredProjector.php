<?php

namespace App\Identity\Infrastructure\DomainEventListeners;

use App\Identity\Domain\User\Events\UserRegistered;
use App\Identity\Infrastructure\IntegrationEvents\UserRegisteredIntegrationEvent;
use App\Shared\Application\Bus\EventBus;

class UserRegisteredProjector
{
    /**
     * Create the event listener.
     */
    public function __construct(private EventBus $eventDispatcher)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $this->eventDispatcher->dispatch(new UserRegisteredIntegrationEvent(
            id: $event->id->value(),
            email: $event->email->value()
        ));
    }
}
