<?php

namespace Tests\Unit\Domain\User;

use App\Domain\Common\Email;
use App\Domain\Common\Exceptions\InsufficientFundsException;
use App\Domain\Common\Money;
use App\Domain\User\Events\UserBalanceCredited;
use App\Domain\User\Events\UserBalanceDebited;
use App\Domain\User\Events\UserNameChanged;
use App\Domain\User\Events\UserPasswordChanged;
use App\Domain\User\Events\UserRegistered;
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

        $this->assertTrue($user->id()->equals($userId));

        $events = $user->releaseEvents();

        $this->assertCount(1, $events);

        $this->assertEquals(new UserRegistered(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com')
        ), $events[0]);
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

        $events = $user->releaseEvents();

        $this->assertCount(2, $events);

        $this->assertEquals(new UserBalanceCredited(
            $userId,
            Money::fromInteger(1000),
            Money::fromInteger(1000),
        ), $events[1]);
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

        $events = $user->releaseEvents();

        $this->assertCount(3, $events);

        $this->assertEquals(new UserBalanceCredited(
            $userId,
            Money::fromInteger(1000),
            Money::fromInteger(1000),
        ), $events[1]);

        $this->assertEquals(new UserBalanceDebited(
            $userId,
            Money::fromInteger(1000),
            Money::fromInteger(0),
        ), $events[2]);
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

    public function testChangeUserNameToSameName(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $user->changeName(UserName::fromString('username'));

        $events = $user->releaseEvents();

        $this->assertCount(1, $events);
    }

    public function testChangeUserNameToOtherName(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $user->changeName(UserName::fromString('other_username'));

        $events = $user->releaseEvents();

        $this->assertCount(2, $events);

        $this->assertEquals(new UserNameChanged(
            $userId,
            UserName::fromString('other_username')
        ), $events[1]);
    }

    public function testChangePasswordToSamePassword(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $user->changePassword(HashedPassword::fromHash('password'));

        $events = $user->releaseEvents();

        $this->assertCount(1, $events);
    }

    public function testChangePasswordToOtherPassword(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            UserName::fromString('username'),
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $user->changePassword(HashedPassword::fromHash('other_password'));

        $events = $user->releaseEvents();

        $this->assertCount(2, $events);

        $this->assertEquals(new UserPasswordChanged(
            $userId,
        ), $events[1]);
    }
}
