<?php

namespace Tests\Feature\Identity\Application\UseCases\Commands\RegisterUser;

use App\Identity\Application\UseCases\Commands\RegisterUser\RegisterUserCommand;
use App\Identity\Application\UseCases\Commands\RegisterUser\RegisterUserCommandHandler;
use App\Identity\Domain\User\Events\UserRegistered;
use App\Identity\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterUserHandlerTest extends TestCase
{
    use RefreshDatabase;

    private RegisterUserCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(RegisterUserCommandHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $command = new RegisterUserCommand(
            email: 'username@test.com',
            password: 'password',
        );

        $this->handler->handle($command);

        $this->assertDatabaseHas('users', [
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
            email: 'username@test.com',
            password: 'password',
        );

        $this->handler->handle($command);
    }
}
