<?php

namespace Tests\Unit\Domain\User;

use App\Domain\Common\Email;
use App\Domain\Common\Exceptions\InsufficientFundsException;
use App\Domain\Common\Money;
use App\Domain\User\HashedPassword;
use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserName;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testSuccessfullyCreateUser(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $this->assertTrue($user->getId()->equals($userId));
    }

    public function testUsersEqualsTrue(): void
    {
        $uuid = Str::uuid()->toString();

        $firstUserId = UserId::fromString($uuid);
        $firstUser = User::register(
            $firstUserId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $secondUserId = UserId::fromString($uuid);
        $secondUser = User::register(
            $secondUserId,
            UserName::fromString('username2'),
            Email::fromString('username2@test.com'),
            HashedPassword::fromHash('password'),
        );

        $this->assertTrue($firstUser->equals($secondUser));
    }

    public function testUsersEqualsFalse(): void
    {
        $firstUserId = UserId::fromString(Str::uuid()->toString());
        $firstUser = User::register(
            $firstUserId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $secondUserId = UserId::fromString(Str::uuid()->toString());
        $secondUser = User::register(
            $secondUserId,
            UserName::fromString('username2'),
            Email::fromString('username2@test.com'),
            HashedPassword::fromHash('password'),
        );

        $this->assertFalse($firstUser->equals($secondUser));
    }

    public function testSuccessfullyCreditBalance(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $user->credit(Money::fromInteger(1000));

        //todo domain events
    }

    public function testSuccessfullyDebitBalance(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $user->credit(Money::fromInteger(1000));
        $user->debit(Money::fromInteger(1000));

        //todo domain events
    }

    public function testDebitUnsufficientFunds(): void
    {
        $this->expectException(InsufficientFundsException::class);

        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $user->debit(Money::fromInteger(1000));
    }
}
