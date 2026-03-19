<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Identity\Infrastructure\Persistence\Eloquent\Model\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
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

    public function testChangeNameSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'name' => 'username',
            'email' => 'username@gmail.com',
            'password' => 'password',
        ]);

        $payload = [
            'name' => 'new_username',
        ];

        $response = $this->postJson(
            route('api.users.change-name', ['id' => $userModel->id]),
            $payload
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $userModel->id,
            'name' => 'new_username',
        ]);
    }

    public function testChangePasswordSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'name' => 'username',
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

        $response->assertStatus(200);

        $userModel->refresh();

        $this->assertTrue($this->hasher->check('new_password', $userModel->password));
    }
}
