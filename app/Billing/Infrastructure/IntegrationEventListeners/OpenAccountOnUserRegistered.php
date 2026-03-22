<?php

namespace App\Billing\Infrastructure\IntegrationEventListeners;

use App\Billing\Application\UseCases\Commands\OpenAccount\OpenAccountCommand;
use App\Identity\Infrastructure\IntegrationEvents\UserRegisteredIntegrationEvent;
use App\Shared\Application\Bus\CommandBus;

class OpenAccountOnUserRegistered
{
    /**
     * Create the event listener.
     */
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegisteredIntegrationEvent $event): void
    {
        $command = new OpenAccountCommand(
            userId: $event->id,
            balance: 0
        );

        $this->commandBus->dispatch($command);
    }
}
