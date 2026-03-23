<?php

namespace Tests\Feature\Identity\Application\UseCases\Commands\RegisterUser;

use App\Identity\Application\UseCases\Commands\RegisterUser\RegisterUserCommand;
use App\Identity\Application\UseCases\Commands\RegisterUser\RegisterUserCommandHandler;
use App\Identity\Infrastructure\DomainEventListeners\UserRegisteredProjector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenAccountOnUserRegisteredTest extends TestCase
{
    use RefreshDatabase;

    private RegisterUserCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = app(RegisterUserCommandHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $command = new RegisterUserCommand(
            email: 'username@test.com',
            password: 'password',
        );

        $result = $this->handler->handle($command);

        $listener = app(UserRegisteredProjector::class);
        $listener->handle($result->events()[0]);

        $this->assertDatabaseHas('users', [
            'email' => 'username@test.com'
        ]);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $result->payload()->value(),
            'balance' => 0
        ]);
    }
}
