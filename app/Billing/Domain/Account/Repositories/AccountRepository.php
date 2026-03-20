<?php

namespace App\Billing\Domain\Account\Repositories;

use App\Billing\Domain\Account\Account;
use App\Billing\Domain\Account\AccountId;
use App\Billing\Domain\Account\UserId;

interface AccountRepository
{
    public function get(AccountId $id): Account;
    public function getByUserId(UserId $userId): Account;
    public function save(Account $account): void;
    public function existsByUserId(UserId $userId): bool;
}
