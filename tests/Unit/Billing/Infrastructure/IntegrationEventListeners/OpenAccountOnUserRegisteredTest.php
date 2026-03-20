<?php

namespace Tests\Unit\Billing\Infrastructure\IntegrationEventListeners;

use App\Billing\Application\UseCases\OpenAccount\OpenAccountCommand;
use App\Billing\Application\UseCases\OpenAccount\OpenAccountHandler;
use App\Billing\Application\UseCases\OpenAccount\OpenAccountResult;
use App\Billing\Infrastructure\IntegrationEventListeners\OpenAccountOnUserRegistered;
use App\Identity\Infrastructure\IntegrationEvents\UserRegisteredIntegrationEvent;
use Illuminate\Support\Str;
use Tests\TestCase;

class OpenAccountOnUserRegisteredTest extends TestCase
{
    public function testHandleSuccessfully(): void
    {
        $userId = Str::uuid()->toString();

        $openAccountHandlerMock = $this->createMock(OpenAccountHandler::class);

        $openAccountHandlerMock
            ->expects($this->once())
            ->method('handle')
            ->with($this->callback(function (OpenAccountCommand $command) use ($userId) {
                return $command->userId === $userId && $command->balance === 0;
            }))
            ->willReturn(new OpenAccountResult(
                id: Str::uuid()->toString(),
                userId: $userId,
                balance: 0,
            ));

        $listener = new OpenAccountOnUserRegistered($openAccountHandlerMock);

        $event = new UserRegisteredIntegrationEvent(
            id: $userId,
            email: 'username@gmail.com'
        );

        $listener->handle($event);
    }
}
