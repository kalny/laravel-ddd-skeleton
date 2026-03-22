<?php

namespace Tests\Feature\Billing\Application\UseCases\Queries\GetAccount;

use App\Billing\Application\DTO\AccountDTO;
use App\Billing\Application\UseCases\Queries\GetAccount\GetAccountQuery;
use App\Billing\Application\UseCases\Queries\GetAccount\GetAccountQueryHandler;
use App\Billing\Domain\Account\Exceptions\AccountNotFoundException;
use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class GetAccountQueryHandlerTest extends TestCase
{
    use DatabaseMigrations;

    private GetAccountQueryHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->handler = app(GetAccountQueryHandler::class);
    }

    public function testHandleSuccessfully(): void
    {
        $userModel = User::factory()->create();
        $accountModel = Account::factory()->create([
            'user_id' => $userModel->id,
        ]);

        $result = $this->handler->handle(new GetAccountQuery(
            userId: $userModel->id,
        ));

        $this->assertEquals(new AccountDTO(
            id: $accountModel->id,
            userId: $userModel->id,
            balance: $accountModel->balance,
        ), $result);
    }

    public function testHandleAccountNotFound(): void
    {
        $this->expectException(AccountNotFoundException::class);

        $this->handler->handle(new GetAccountQuery(
            userId: 'wrong-id',
        ));
    }
}
