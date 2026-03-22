<?php

namespace App\Billing\Application\UseCases\Queries\GetAccount;

use App\Billing\Application\DTO\AccountDTO;
use App\Billing\Domain\Account\Exceptions\AccountNotFoundException;
use App\Billing\Infrastructure\Persistence\Eloquent\Models\Account;

class GetAccountQueryHandler
{
    public function handle(GetAccountQuery $query): AccountDTO
    {
        $accountModel = Account::query()
            ->where('user_id', $query->userId)
            ->first();

        if (!$accountModel) {
            throw new AccountNotFoundException();
        }

        return new AccountDTO(
            id: $accountModel->id,
            userId: $accountModel->user_id,
            balance: $accountModel->balance
        );
    }
}
