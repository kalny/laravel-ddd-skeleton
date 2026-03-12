<?php

namespace Tests\Unit\Domain\User;

use App\Domain\User\UserId;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserIdTest extends TestCase
{
    public function testSuccessfulltCreateUserIdFromString(): void
    {
        $uuid = Str::uuid()->toString();

        $userId = UserId::fromString($uuid);
        $otherUserId = UserId::fromString($uuid);

        $this->assertTrue($userId->equals($otherUserId));
    }
}
