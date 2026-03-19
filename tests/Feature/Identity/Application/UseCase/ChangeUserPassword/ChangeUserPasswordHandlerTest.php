<?php

namespace Tests\Feature\Identity\Application\UseCase\ChangeUserPassword;

use App\Identity\Application\UseCase\ChangeUserPassword\ChangeUserPasswordCommand;
use App\Identity\Application\UseCase\ChangeUserPassword\ChangeUserPasswordHandler;
use App\Identity\Domain\User\Event\UserPasswordChanged;
use App\Identity\Infrastructure\Persistence\Eloquent\Model\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChangeUserPasswordHandlerTest extends TestCase
{
    use RefreshDatabase;

    private ChangeUserPasswordHandler $handler;
    private Hasher $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(ChangeUserPasswordHandler::class);
        $this->hasher = app(Hasher::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'name' => 'username',
            'email' => 'username@test.com',
            'password' => 'password',
        ]);

        $command = new ChangeUserPasswordCommand(
            id: $userModel->id,
            password: 'new_password'
        );

        $this->handler->handle($command);

        $user = User::query()
            ->where('id', $userModel->id)
            ->first();

        $this->assertTrue($this->hasher->check('new_password', $user->password));

        Event::assertDispatched(UserPasswordChanged::class);
    }
}
