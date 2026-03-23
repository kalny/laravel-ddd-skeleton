<?php

namespace Tests\Feature\Identity\Application\UseCases\Commands\ChangeUserEmail;

use App\Identity\Application\UseCases\Commands\ChangeUserEmail\ChangeUserEmailCommand;
use App\Identity\Application\UseCases\Commands\ChangeUserEmail\ChangeUserEmailCommandHandler;
use App\Identity\Domain\User\Events\UserEmailChanged;
use App\Identity\Domain\User\Exceptions\EmailAlreadyTakenException;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeUserEmailHandlerTest extends TestCase
{
    use RefreshDatabase;

    private ChangeUserEmailCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = app(ChangeUserEmailCommandHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@test.com',
            'password' => 'password',
        ]);

        $command = new ChangeUserEmailCommand(
            id: $userModel->id,
            email: 'new_username@test.com'
        );

        $result = $this->handler->handle($command);

        $this->assertInstanceOf(UserEmailChanged::class, $result->events()[0]);

        $this->assertDatabaseHas('users', [
            'id' => $userModel->id,
            'email' => 'new_username@test.com',
        ]);
    }

    public function testHandleSuccessfullyWithSameEmail(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@test.com',
            'password' => 'password',
        ]);

        $command = new ChangeUserEmailCommand(
            id: $userModel->id,
            email: 'username@test.com'
        );

        $result = $this->handler->handle($command);

        $this->assertSame(0, count($result->events()));

        $this->assertDatabaseHas('users', [
            'id' => $userModel->id,
            'email' => 'username@test.com',
        ]);
    }

    public function testHandleSuccessfullyWithAlreadyExistingEmail(): void
    {
        $this->expectException(EmailAlreadyTakenException::class);

        User::factory()->create([
            'email' => 'other_username@test.com',
            'password' => 'password',
        ]);

        $userModel = User::factory()->create([
            'email' => 'username@test.com',
            'password' => 'password',
        ]);

        $command = new ChangeUserEmailCommand(
            id: $userModel->id,
            email: 'other_username@test.com'
        );

        $this->handler->handle($command);
    }
}
