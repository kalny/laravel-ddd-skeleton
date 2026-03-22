<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[dataProvider('changePasswordValidationDataProvider')]
    public function testChangePasswordValidationFailed(array $payload, array $errors): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => 'password',
        ]);

        Sanctum::actingAs($userModel);

        $response = $this->postJson(
            route('api.users.change-password', ['id' => $userModel->id]),
            $payload
        );

        $response->assertStatus(422);

        $response->assertJsonPath('errors', $errors);
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

    #[dataProvider('changeEmailValidationDataProvider')]
    public function testChangeEmailValidationFailed(array $payload, array $errors): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => 'password',
        ]);

        Sanctum::actingAs($userModel);

        $response = $this->postJson(
            route('api.users.change-email', ['id' => $userModel->id]),
            $payload
        );

        $response->assertStatus(422);

        $response->assertJsonPath('errors', $errors);
    }

    public static function changePasswordValidationDataProvider(): array
    {
        return [
            [
                'payload' => [
                ],
                'errors' => [
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'password' => 123456,
                ],
                'errors' => [
                    'password' => [
                        'The password field must be a string.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'password' => 'abc',
                ],
                'errors' => [
                    'password' => [
                        'The password field must be at least 6 characters.'
                    ]
                ]
            ],
        ];
    }

    public static function changeEmailValidationDataProvider(): array
    {
        return [
            [
                'payload' => [
                ],
                'errors' => [
                    'email' => [
                        'The email field is required.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'email' => 'wrong_email'
                ],
                'errors' => [
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ],
        ];
    }
}
