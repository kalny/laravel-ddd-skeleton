<?php

namespace Tests\Unit\Billing\Infrastructure\IntegrationEventListeners;

use App\Billing\Application\UseCases\Commands\OpenAccount\OpenAccountCommand;
use App\Billing\Infrastructure\IntegrationEventListeners\OpenAccountOnUserRegistered;
use App\Identity\Infrastructure\IntegrationEvents\UserRegisteredIntegrationEvent;
use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\CommandResult;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class OpenAccountOnUserRegisteredTest extends TestCase
{
    public function testHandleSuccessfully(): void
    {
        $userId = Str::uuid()->toString();

        $commandBusMock = Mockery::mock(CommandBus::class);

        $commandBusMock
            ->shouldReceive('dispatch')
            ->once()
            ->with(Mockery::on(function (OpenAccountCommand $command) use ($userId) {
                return $command->userId === $userId && $command->balance === 0;
            }))
            ->andReturn(CommandResult::success());

        $listener = new OpenAccountOnUserRegistered($commandBusMock);

        $event = new UserRegisteredIntegrationEvent(
            id: $userId,
            email: 'username@gmail.com'
        );

        $listener->handle($event);
    }
}
