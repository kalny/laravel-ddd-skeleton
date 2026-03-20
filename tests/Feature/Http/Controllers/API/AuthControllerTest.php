<?php

namespace Tests\Feature\Http\Controllers\API;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
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
            ]
        ]);
        $response->assertJsonPath('data.email', 'username@test.com');

        $this->assertDatabaseHas('users', [
            'email' => 'username@test.com'
        ]);
    }
}
