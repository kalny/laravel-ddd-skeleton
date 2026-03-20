<?php

namespace App\Billing\Infrastructure\Persistence\Eloquent\Repositories;

use App\Billing\Domain\Account\Account;
use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\Exceptions\AccountNotFoundException;
use App\Billing\Domain\Account\Money;
use App\Billing\Domain\Account\Repositories\AccountRepository;
use App\Billing\Domain\Account\UserId;
use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account as AccountModel;
use App\Shared\Infrastructure\Services\ReflectionService;
use Throwable;

class EloquentAccountRepository implements AccountRepository
{
    public function __construct(private readonly ReflectionService $reflectionService)
    {
    }

    public function get(AccountId $id): Account
    {
        $accountModel = AccountModel::query()
            ->where('id', $id->value())
            ->first();

        if (!$accountModel) {
            throw new AccountNotFoundException('Account not found');
        }

        /** @var Account $accountEntity */
        $accountEntity = $this->reflectionService->createObject(Account::class, [
            'id' => AccountId::fromString($accountModel->id),
            'userId' => UserId::fromString($accountModel->user_id),
            'balance' => Money::fromInteger($accountModel->balance),
        ]);

        return $accountEntity;
    }

    public function getByUserId(UserId $userId): Account
    {
        $accountModel = AccountModel::query()
            ->where('user_id', $userId->value())
            ->first();

        if (!$accountModel) {
            throw new AccountNotFoundException('Account not found');
        }

        /** @var Account $accountEntity */
        $accountEntity = $this->reflectionService->createObject(Account::class, [
            'id' => AccountId::fromString($accountModel->id),
            'userId' => UserId::fromString($accountModel->user_id),
            'balance' => Money::fromInteger($accountModel->balance),
        ]);

        return $accountEntity;
    }

    /**
     * @throws Throwable
     */
    public function save(Account $account): void
    {
        $accountModel = AccountModel::query()
            ->where('id', $account->id()->value())
            ->first();

        if (!$accountModel) {
            $accountModel = new AccountModel();
        }

        $balance = $this->reflectionService->getValue($account, 'balance');
        $balanceValue = $this->reflectionService->getValue($balance, 'amount');

        $userId = $this->reflectionService->getValue($account, 'userId')->value();

        $accountModel->id = $account->id()->value();
        $accountModel->user_id = $userId;
        $accountModel->balance = $balanceValue;

        $accountModel->saveOrFail();
    }

    public function existsByUserId(UserId $userId): bool
    {
        return AccountModel::query()
            ->where('user_id', $userId->value())
            ->exists();
    }
}
