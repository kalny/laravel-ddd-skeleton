<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[dataProvider('registerValidationDataProvider')]
    public function testRegisterValidationFailed(array $payload, array $errors): void
    {
        $response = $this->postJson(route('api.auth.register'), $payload);

        $response->assertStatus(422);
        $response->assertJsonPath('errors', $errors);
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

    #[dataProvider('loginValidationDataProvider')]
    public function testLoginValidationFailed(array $payload, array $errors): void
    {
        $response = $this->postJson(route('api.auth.login'), $payload);

        $response->assertStatus(422);
        $response->assertJsonPath('errors', $errors);
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

    public static function registerValidationDataProvider(): array
    {
        return [
            [
                'payload' => [
                    'password' => 'password',
                ],
                'errors' => [
                    'email' => [
                        'The email field is required.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'email' => 'wrong_email',
                    'password' => 'password',
                ],
                'errors' => [
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'email' => 'username@gmail.com',
                ],
                'errors' => [
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'email' => 'username@gmail.com',
                    'password' => 1,
                ],
                'errors' => [
                    'password' => [
                        'The password field must be a string.',
                        'The password field must be at least 6 characters.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'email' => 'username@gmail.com',
                    'password' => 1234567890,
                ],
                'errors' => [
                    'password' => [
                        'The password field must be a string.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'email' => 'username@gmail.com',
                    'password' => '123',
                ],
                'errors' => [
                    'password' => [
                        'The password field must be at least 6 characters.'
                    ]
                ]
            ],
        ];
    }

    public static function loginValidationDataProvider(): array
    {
        return [
            [
                'payload' => [
                    'password' => 'password',
                ],
                'errors' => [
                    'email' => [
                        'The email field is required.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'email' => 'wrong_email',
                    'password' => 'password',
                ],
                'errors' => [
                    'email' => [
                        'The email field must be a valid email address.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'email' => 'username@gmail.com',
                ],
                'errors' => [
                    'password' => [
                        'The password field is required.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'email' => 'username@gmail.com',
                    'password' => 1,
                ],
                'errors' => [
                    'password' => [
                        'The password field must be a string.',
                    ]
                ]
            ]
        ];
    }
}
