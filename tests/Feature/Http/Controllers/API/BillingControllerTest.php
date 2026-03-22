<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Billing\Domain\Account\Events\AccountBalanceCredited;
use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class BillingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function testDepositSuccessfully(): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => 'password',
        ]);

        $accountModel = Account::factory()->create([
            'user_id' => $userModel->id,
        ]);

        $payload = [
            'amount' => '1000.50',
            'currency' => 'USD',
        ];

        Sanctum::actingAs($userModel);

        $response = $this->postJson(
            route('api.users.deposit', ['id' => $userModel->id]),
            $payload
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('accounts', [
            'id' => $accountModel->id,
            'user_id' => $userModel->id,
            'balance' => '100050',
            'currency' => 'USD',
        ]);

        Event::assertDispatched(AccountBalanceCredited::class);
    }

    #[dataProvider('depositValidationDataProvider')]
    public function testDepositValidationFailed(array $payload, array $errors): void
    {
        $userModel = User::factory()->create([
            'email' => 'username@gmail.com',
            'password' => 'password',
        ]);

        Account::factory()->create([
            'user_id' => $userModel->id,
        ]);

        Sanctum::actingAs($userModel);

        $response = $this->postJson(
            route('api.users.deposit', ['id' => $userModel->id]),
            $payload
        );

        $response->assertStatus(422);

        $response->assertJsonPath('errors', $errors);
    }

    public static function depositValidationDataProvider(): array
    {
        return [
            [
                'payload' => [
                    'currency' => 'USD',
                ],
                'errors' => [
                    'amount' => [
                        'The amount field is required.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'amount' => 1,
                    'currency' => 'USD',
                ],
                'errors' => [
                    'amount' => [
                        'The amount field must be a string.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'amount' => '12345678901234567890.12',
                    'currency' => 'USD',
                ],
                'errors' => [
                    'amount' => [
                        'The amount field must not be greater than 20 characters.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'amount' => '12345 2',
                    'currency' => 'USD',
                ],
                'errors' => [
                    'amount' => [
                        'The amount field format is invalid.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'amount' => '1234.12',
                ],
                'errors' => [
                    'currency' => [
                        'The currency field is required.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'amount' => '1234.12',
                    'currency' => 123,
                ],
                'errors' => [
                    'currency' => [
                        'The selected currency is invalid.'
                    ]
                ]
            ],
            [
                'payload' => [
                    'amount' => '1234.12',
                    'currency' => 'ABC',
                ],
                'errors' => [
                    'currency' => [
                        'The selected currency is invalid.'
                    ]
                ]
            ],
        ];
    }
}
