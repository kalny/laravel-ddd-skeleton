<?php

namespace Tests\Unit\Identity\Domain\User;

use App\Identity\Domain\User\UserId;
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
