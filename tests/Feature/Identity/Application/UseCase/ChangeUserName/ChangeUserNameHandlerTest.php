<?php

namespace Tests\Feature\Identity\Application\UseCase\ChangeUserName;

use App\Identity\Application\UseCase\ChangeUserName\ChangeUserNameCommand;
use App\Identity\Application\UseCase\ChangeUserName\ChangeUserNameHandler;
use App\Identity\Domain\User\Event\UserNameChanged;
use App\Identity\Infrastructure\Persistence\Eloquent\Model\User;
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
