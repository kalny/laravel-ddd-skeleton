<?php

namespace Tests\Feature\Billing\Application\UseCases\OpenAccount;

use App\Billing\Application\UseCases\OpenAccount\OpenAccountCommand;
use App\Billing\Application\UseCases\OpenAccount\OpenAccountHandler;
use App\Billing\Domain\Account\Events\AccountOpened;
use App\Billing\Domain\Account\Exceptions\AccountAlreadyExistsException;
use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class OpenAccountHandlerTest extends TestCase
{
    use RefreshDatabase;

    private OpenAccountHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(OpenAccountHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create();

        $command = new OpenAccountCommand(
            userId: $userModel->id,
            balance: 100,
        );

        $result = $this->handler->handle($command);

        $this->assertEquals($userModel->id, $result->userId);
        $this->assertEquals(100, $result->balance);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $userModel->id,
            'balance' => 100,
        ]);

        Event::assertDispatched(AccountOpened::class);
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
