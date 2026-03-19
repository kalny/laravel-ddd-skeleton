<?php

namespace Tests\Feature\Identity\Application\UseCase\RegisterUser;

use App\Identity\Application\UseCase\RegisterUser\RegisterUserCommand;
use App\Identity\Application\UseCase\RegisterUser\RegisterUserHandler;
use App\Identity\Domain\User\Event\UserRegistered;
use App\Identity\Domain\User\Exception\UserAlreadyExistsException;
use App\Identity\Infrastructure\Persistence\Eloquent\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterUserHandlerTest extends TestCase
{
    use RefreshDatabase;

    private RegisterUserHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(RegisterUserHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $command = new RegisterUserCommand(
            name: 'username',
            email: 'username@test.com',
            password: 'password',
        );

        $result = $this->handler->handle($command);

        $this->assertEquals('username', $result->name);
        $this->assertEquals('username@test.com', $result->email);

        $this->assertDatabaseHas('users', [
            'name' => 'username',
            'email' => 'username@test.com'
        ]);

        Event::assertDispatched(UserRegistered::class);
    }

    public function testHandleUserAlreadyExists(): void
    {
        $this->expectException(UserAlreadyExistsException::class);

        User::factory()->create([
            'email' => 'username@test.com',
        ]);

        $command = new RegisterUserCommand(
            name: 'username',
            email: 'username@test.com',
            password: 'password',
        );

        $this->handler->handle($command);
    }
}
