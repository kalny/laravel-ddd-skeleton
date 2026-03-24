<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\AccountId;
use PHPUnit\Framework\TestCase;

class AccountIdTest extends TestCase
{
    public function testSuccessfullyCreateAccountIdFromString(): void
    {
        $uuid = 'user-id';

        $accountId = AccountId::fromString($uuid);
        $otherAccountId = AccountId::fromString($uuid);

        $this->assertTrue($accountId->equals($otherAccountId));
    }
}
