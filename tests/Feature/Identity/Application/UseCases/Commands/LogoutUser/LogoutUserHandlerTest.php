<?php

namespace Tests\Feature\Identity\Application\UseCases\Commands\LogoutUser;

use App\Identity\Application\UseCases\Commands\LoginUser\LoginUserCommandHandler;
use App\Identity\Application\UseCases\Commands\LogoutUser\LogoutUserCommand;
use App\Identity\Application\UseCases\Commands\LogoutUser\LogoutUserCommandHandler;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutUserHandlerTest extends TestCase
{
    use RefreshDatabase;

    private LoginUserCommandHandler $loginHandler;
    private LogoutUserCommandHandler $logoutHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loginHandler = app(LoginUserCommandHandler::class);
        $this->logoutHandler = app(LogoutUserCommandHandler::class);
    }

    public function testHandleSuccessFully(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Sanctum::actingAs($userModel);

        $this->logoutHandler->handle(new LogoutUserCommand(
            id: $userModel->id,
        ));

        // specify logic
        $this->assertTrue(true);
    }
}
