<?php

namespace Tests\Feature\Identity\Application\UseCases\ChangeUserEmail;

use App\Identity\Application\UseCases\ChangeUserEmail\ChangeUserEmailCommand;
use App\Identity\Application\UseCases\ChangeUserEmail\ChangeUserEmailHandler;
use App\Identity\Domain\User\Events\UserEmailChanged;
use App\Identity\Domain\User\Exceptions\EmailAlreadyTakenException;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChangeUserEmailHandlerTest extends TestCase
{
    use RefreshDatabase;

    private ChangeUserEmailHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();

        $this->handler = app(ChangeUserEmailHandler::class);
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

        $this->handler->handle($command);

        $this->assertDatabaseHas('users', [
            'id' => $userModel->id,
            'email' => 'new_username@test.com',
        ]);

        Event::assertDispatched(UserEmailChanged::class);
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

        $this->handler->handle($command);

        $this->assertDatabaseHas('users', [
            'id' => $userModel->id,
            'email' => 'username@test.com',
        ]);

        Event::assertNotDispatched(UserEmailChanged::class);
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
