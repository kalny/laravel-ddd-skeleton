<?php

namespace Tests\Unit\Identity\Domain\User;

use App\Identity\Domain\User\UserId;
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
