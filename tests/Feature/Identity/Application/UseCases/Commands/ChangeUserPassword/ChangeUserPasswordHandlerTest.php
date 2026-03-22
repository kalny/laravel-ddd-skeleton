<?php

namespace Tests\Feature\Identity\Application\UseCases\Commands\ChangeUserPassword;

use App\Identity\Application\UseCases\Commands\ChangeUserPassword\ChangeUserPasswordCommand;
use App\Identity\Application\UseCases\Commands\ChangeUserPassword\ChangeUserPasswordCommandHandler;
use App\Identity\Domain\User\Events\UserPasswordChanged;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChangeUserPasswordHandlerTest extends TestCase
{
    use RefreshDatabase;

    private ChangeUserPasswordCommandHandler $handler;
    private Hasher $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(ChangeUserPasswordCommandHandler::class);
        $this->hasher = app(Hasher::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create([
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
