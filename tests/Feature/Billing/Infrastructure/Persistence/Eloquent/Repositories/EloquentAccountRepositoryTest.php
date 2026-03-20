<?php

namespace Tests\Feature\Billing\Infrastructure\Persistence\Eloquent\Repositories;

use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Currency;
use App\Billing\Domain\Account\Exceptions\AccountNotFoundException;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\UserId;
use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account;
use App\Billing\Infrastructure\Persistence\Eloquent\Repositories\EloquentAccountRepository;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use Throwable;

class EloquentAccountRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentAccountRepository $accounts;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accounts = app(EloquentAccountRepository::class);
    }

    public function testSuccessfullyGetAccount(): void
    {
        $uuid = Str::uuid()->toString();

        $accountModel = Account::factory()->create([
            'id' => $uuid,
        ]);

        $account = $this->accounts->get(AccountId::fromString($uuid));

        $this->assertTrue($account->id()->equals(AccountId::fromString($accountModel->id)));
    }

    public function testGetNotExistedAccount(): void
    {
        $this->expectException(AccountNotFoundException::class);
        $uuid = Str::uuid()->toString();

        $this->accounts->get(AccountId::fromString($uuid));
    }

    public function testSuccessfullySaveExistingAccount(): void
    {
        $uuid = Str::uuid()->toString();

        Account::factory()->create([
            'id' => $uuid,
            'balance' => 1000
        ]);

        $usd = Currency::USD();

        $account = $this->accounts->get(AccountId::fromString($uuid));

        $account->credit(Money::fromMinor(500, $usd));

        $this->accounts->save($account);

        $this->assertDatabaseHas('accounts', [
            'id' => $uuid,
            'balance' => 1500
        ]);
    }

    /**
     * @throws Throwable
     */
    public function testSuccessfullySaveNewAccount(): void
    {
        $userIdValue = Str::uuid()->toString();
        User::factory()->create([
            'id' => $userIdValue,
        ]);

        $uuid = Str::uuid()->toString();

        $usd = Currency::USD();

        $accountId = AccountId::fromString($uuid);
        $userId = \App\Billing\Domain\Account\UserId::fromString($userIdValue);

        $account = \App\Billing\Domain\Account\Account::open(
            $accountId,
            $userId,
            $usd
        );

        $this->accounts->save($account);

        $this->assertDatabaseHas('accounts', [
            'id' => $uuid,
            'user_id' => $userIdValue,
            'balance' => 0,
        ]);
    }

    public function testSuccessfullyGetAccountByUserId(): void
    {
        $uuid = Str::uuid()->toString();

        $accountModel = Account::factory()->create([
            'id' => $uuid,
        ]);

        $account = $this->accounts->getByUserId(UserId::fromString($accountModel->user_id));

        $this->assertTrue($account->id()->equals(AccountId::fromString($accountModel->id)));
    }

    public function testGetNotExistedAccountByUserId(): void
    {
        $this->expectException(AccountNotFoundException::class);
        $uuid = Str::uuid()->toString();

        $this->accounts->getByUserId(UserId::fromString($uuid));
    }
}
