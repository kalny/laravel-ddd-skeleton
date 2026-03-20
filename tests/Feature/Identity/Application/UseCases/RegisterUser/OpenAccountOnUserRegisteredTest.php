<?php

namespace Tests\Feature\Identity\Application\UseCases\RegisterUser;

use App\Identity\Application\UseCases\RegisterUser\RegisterUserCommand;
use App\Identity\Application\UseCases\RegisterUser\RegisterUserHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenAccountOnUserRegisteredTest extends TestCase
{
    use RefreshDatabase;

    private RegisterUserHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = app(RegisterUserHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $command = new RegisterUserCommand(
            email: 'username@test.com',
            password: 'password',
        );

        $result = $this->handler->handle($command);

        $this->assertEquals('username@test.com', $result->email);

        $this->assertDatabaseHas('users', [
            'email' => 'username@test.com'
        ]);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $result->id,
            'balance' => 0
        ]);
    }
}
