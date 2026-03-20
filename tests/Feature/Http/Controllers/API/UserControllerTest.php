<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private Hasher $hasher;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();

        $this->hasher = app(Hasher::class);
    }

    public function testChangePasswordSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => 'password',
        ]);

        $payload = [
            'password' => 'new_password',
        ];

        Sanctum::actingAs($userModel);

        $response = $this->postJson(
            route('api.users.change-password', ['id' => $userModel->id]),
            $payload
        );

        $response->assertStatus(200);

        $userModel->refresh();

        $this->assertTrue($this->hasher->check('new_password', $userModel->password));
    }

    public function testChangePasswordUnauthorized(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => 'password',
        ]);

        $payload = [
            'password' => 'new_password',
        ];

        $response = $this->postJson(
            route('api.users.change-password', ['id' => $userModel->id]),
            $payload
        );

        $response->assertStatus(401);
    }

    public function testChangeEmailSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => 'password',
        ]);

        $payload = [
            'email' => 'new_username@gmail.com',
        ];

        Sanctum::actingAs($userModel);

        $response = $this->postJson(
            route('api.users.change-email', ['id' => $userModel->id]),
            $payload
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $userModel->id,
            'email' => $payload['email'],
        ]);
    }

    public function testChangeEmailUnauthorized(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => 'password',
        ]);

        $payload = [
            'email' => 'new_username@gmail.com',
        ];

        $response = $this->postJson(
            route('api.users.change-email', ['id' => $userModel->id]),
            $payload
        );

        $response->assertStatus(401);
    }
}
