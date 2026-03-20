<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\AccountId;
use Illuminate\Support\Str;
use Tests\TestCase;

class AccountIdTest extends TestCase
{
    public function testSuccessfullyCreateAccountIdFromString(): void
    {
        $uuid = Str::uuid()->toString();

        $accountId = AccountId::fromString($uuid);
        $otherAccountId = AccountId::fromString($uuid);

        $this->assertTrue($accountId->equals($otherAccountId));
    }
}
