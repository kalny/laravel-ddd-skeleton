<?php

namespace Tests\Feature\Identity\Application\UseCases\LogoutUser;

use App\Identity\Application\UseCases\LoginUser\LoginUserHandler;
use App\Identity\Application\UseCases\LogoutUser\LogoutUserHandler;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutUserHandlerTest extends TestCase
{
    use RefreshDatabase;

    private LoginUserHandler $loginHandler;
    private LogoutUserHandler $logoutHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loginHandler = app(LoginUserHandler::class);
        $this->logoutHandler = app(LogoutUserHandler::class);
    }

    public function testHandleSuccessFully(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => Hash::make('password'),
        ]);

        Sanctum::actingAs($userModel);

        $this->logoutHandler->handle();

        $this->assertDatabaseMissing('personal_access_tokens', [
            'tokenable_id' => $userModel->id,
        ]);
    }
}
