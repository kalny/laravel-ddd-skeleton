<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function testRegisterSuccessfully(): void
    {
        $payload = [
            'email' => 'username@test.com',
            'password' => 'password',
        ];

        $response = $this->postJson(route('api.auth.register'), $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'token'
            ]
        ]);
        $response->assertJsonPath('data.email', 'username@test.com');

        $this->assertDatabaseHas('users', [
            'email' => 'username@test.com'
        ]);
    }

    public function testLoginSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $payload = [
            'email' => 'username@gmail.com',
            'password' => 'password',
        ];

        $response = $this->postJson(route('api.auth.login'), $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'email',
                'token'
            ]
        ]);
        $response->assertJsonPath('data.id', $userModel->id);
        $response->assertJsonPath('data.email', $userModel->email);
    }

    public function testLogoutSuccessfully(): void
    {
        $userModel = User::factory()->create();

        $token = $userModel->createToken('api-token');

        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token->plainTextToken
        )->postJson(route('api.auth.logout'));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id
        ]);
    }

    public function testLogoutUnauthorized(): void
    {
        $response = $this->postJson(route('api.auth.logout'));

        $response->assertStatus(401);
    }
}
