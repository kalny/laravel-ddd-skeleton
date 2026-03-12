<?php

namespace Tests\Feature\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testRegisterSuccessfully(): void
    {
        $payload = [
            'name' => 'username',
            'email' => 'username@test.com',
            'password' => 'password',
        ];

        $response = $this->postJson(route('api.auth.register'), $payload);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
            ]
        ]);
        $response->assertJsonPath('data.name', 'username');
        $response->assertJsonPath('data.email', 'username@test.com');

        $this->assertDatabaseHas('users', [
            'name' => 'username',
            'email' => 'username@test.com'
        ]);
    }
}
