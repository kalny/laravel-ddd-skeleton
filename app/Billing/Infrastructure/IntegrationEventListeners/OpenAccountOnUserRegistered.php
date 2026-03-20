<?php

namespace App\Billing\Infrastructure\IntegrationEventListeners;

use App\Billing\Application\UseCases\OpenAccount\OpenAccount;
use App\Billing\Application\UseCases\OpenAccount\OpenAccountCommand;
use App\Identity\Infrastructure\IntegrationEvents\UserRegisteredIntegrationEvent;

class OpenAccountOnUserRegistered
{
    /**
     * Create the event listener.
     */
    public function __construct(private readonly OpenAccount $openAccountHandler)
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

        $this->openAccountHandler->handle($command);
    }
}
