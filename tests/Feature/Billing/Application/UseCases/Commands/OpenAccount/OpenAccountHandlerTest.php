<?php

namespace Tests\Feature\Billing\Application\UseCases\Commands\OpenAccount;

use App\Billing\Application\UseCases\Commands\OpenAccount\OpenAccountCommand;
use App\Billing\Application\UseCases\Commands\OpenAccount\OpenAccountCommandHandler;
use App\Billing\Domain\Account\Events\AccountOpened;
use App\Billing\Domain\Account\Exceptions\AccountAlreadyExistsException;
use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OpenAccountHandlerTest extends TestCase
{
    use RefreshDatabase;

    private OpenAccountCommandHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = app(OpenAccountCommandHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create();

        $command = new OpenAccountCommand(
            userId: $userModel->id,
            balance: 100,
        );

        $result = $this->handler->handle($command);

        $this->assertInstanceOf(AccountOpened::class, $result->events()[0]);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $userModel->id,
            'balance' => 100,
        ]);
    }

    public function testHandleAccountAlreadyExists(): void
    {
        $this->expectException(AccountAlreadyExistsException::class);

        $userModel = User::factory()->create([
            'email' => 'username@test.com',
        ]);

        Account::factory()->create([
            'user_id' => $userModel->id,
        ]);

        $command = new OpenAccountCommand(
            userId: $userModel->id,
            balance: 100,
        );

        $this->handler->handle($command);
    }
}
