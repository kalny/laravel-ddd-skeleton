<?php

namespace Tests\Feature\Http\Controllers\API;

use App\Billing\Domain\Account\Events\AccountBalanceCredited;
use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;
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
            'userId' => $userModel->id,
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
}
