<?php

namespace Tests\Feature\Billing\Application\UseCases\Commands\Deposit;

use App\Billing\Application\UseCases\Commands\Deposit\DepositCommand;
use App\Billing\Application\UseCases\Commands\Deposit\DepositCommandHandler;
use App\Billing\Domain\Account\Events\AccountBalanceCredited;
use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class DepositHandlerTest extends TestCase
{
    use RefreshDatabase;

    private DepositCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(DepositCommandHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create();
        Account::factory()->create([
            'user_id' => $userModel->id,
        ]);

        $command = new DepositCommand(
            userId: $userModel->id,
            amount: '100.50',
            currency: 'USD',
        );

        $this->handler->handle($command);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $userModel->id,
            'balance' => '10050',
        ]);

        Event::assertDispatched(AccountBalanceCredited::class);
    }
}
