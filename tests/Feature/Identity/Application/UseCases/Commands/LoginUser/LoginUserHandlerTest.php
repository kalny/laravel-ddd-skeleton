<?php

namespace Tests\Feature\Identity\Application\UseCases\Commands\LoginUser;

use App\Identity\Application\UseCases\Commands\LoginUser\LoginUserCommand;
use App\Identity\Application\UseCases\Commands\LoginUser\LoginUserCommandHandler;
use App\Identity\Domain\User\Exceptions\InvalidCredentialsException;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginUserHandlerTest extends TestCase
{
    use RefreshDatabase;

    private LoginUserCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(LoginUserCommandHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $command = new LoginUserCommand(
            email: 'username@gmail.com',
            password: 'password'
        );

        $result = $this->handler->handle($command);

        $this->assertSame($userModel->id, $result->payload()->value());
    }

    public function testHandleUserNotFound(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        $command = new LoginUserCommand(
            email: 'username@gmail.com',
            password: 'password'
        );

        $this->handler->handle($command);
    }

    public function testHandleUInvalidCredentials(): void
    {
        $this->expectException(InvalidCredentialsException::class);

        User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => Hash::make('other_password'),
        ]);

        $command = new LoginUserCommand(
            email: 'username@gmail.com',
            password: 'password'
        );

        $this->handler->handle($command);
    }
}
