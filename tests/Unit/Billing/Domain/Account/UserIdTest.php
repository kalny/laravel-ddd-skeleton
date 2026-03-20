<?php

namespace Tests\Unit\Billing\Domain\Account;

use App\Billing\Domain\Account\UserId;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserIdTest extends TestCase
{
    public function testSuccessfullyCreateUserIdFromString(): void
    {
        $uuid = Str::uuid()->toString();

        $userId = UserId::fromString($uuid);
        $otherUserId = UserId::fromString($uuid);

        $this->assertTrue($userId->equals($otherUserId));
    }
}
