<?php

namespace Tests\Feature\Application\UseCase\ChangeUserName;

use App\Application\UseCase\ChangeUserName\ChangeUserNameCommand;
use App\Application\UseCase\ChangeUserName\ChangeUserNameHandler;
use App\Domain\User\Events\UserNameChanged;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChangeUserNameHandlerTest extends TestCase
{
    use RefreshDatabase;

    private ChangeUserNameHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(ChangeUserNameHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'name' => 'username',
            'email' => 'username@test.com',
        ]);

        $command = new ChangeUserNameCommand(
            id: $userModel->id,
            name: 'new_username'
        );

        $this->handler->handle($command);

        $this->assertDatabaseHas('users', [
            'id' => $userModel->id,
            'name' => 'new_username',
            'email' => 'username@test.com'
        ]);

        Event::assertDispatched(UserNameChanged::class);
    }
}
