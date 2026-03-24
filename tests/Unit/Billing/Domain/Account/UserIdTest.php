<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\UserId;
use PHPUnit\Framework\TestCase;

class UserIdTest extends TestCase
{
    public function testSuccessfullyCreateUserIdFromString(): void
    {
        $uuid = 'user-id';

        $userId = UserId::fromString($uuid);
        $otherUserId = UserId::fromString($uuid);

        $this->assertTrue($userId->equals($otherUserId));
    }
}
