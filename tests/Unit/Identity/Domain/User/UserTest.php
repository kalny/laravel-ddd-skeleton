<?php

namespace Tests\Unit\Identity\Domain\User;

use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Events\UserEmailChanged;
use App\Identity\Domain\User\Events\UserPasswordChanged;
use App\Identity\Domain\User\Events\UserRegistered;
use App\Identity\Domain\User\HashedPassword;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testSuccessfullyCreateUser(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $this->assertTrue($user->id()->equals($userId));

        $events = $user->releaseEvents();

        $this->assertCount(1, $events);

        $this->assertEquals(new UserRegistered(
            $userId,
            Email::fromString('username@test.com')
        ), $events[0]);
    }

    public function testUsersEqualsTrue(): void
    {
        $uuid = Str::uuid()->toString();

        $firstUserId = UserId::fromString($uuid);
        $firstUser = User::register(
            $firstUserId,
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $secondUserId = UserId::fromString($uuid);
        $secondUser = User::register(
            $secondUserId,
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
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $secondUserId = UserId::fromString(Str::uuid()->toString());
        $secondUser = User::register(
            $secondUserId,
            Email::fromString('username2@test.com'),
            HashedPassword::fromHash('password'),
        );

        $this->assertFalse($firstUser->equals($secondUser));
    }

    public function testChangePasswordToSamePassword(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
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

    public function testChangeEmailToSameEmail(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $user->changeEmail(Email::fromString('username@test.com'));

        $events = $user->releaseEvents();

        $this->assertCount(1, $events);
    }

    public function testChangeEmailToOtherEmail(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $user->changeEmail(Email::fromString('other_username@test.com'));

        $events = $user->releaseEvents();

        $this->assertCount(2, $events);

        $this->assertEquals(new UserEmailChanged(
            $userId,
            Email::fromString('other_username@test.com')
        ), $events[1]);
    }

    public function testUserHasEmailTrue(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $this->assertTrue($user->hasEmail(Email::fromString('username@test.com')));
    }

    public function testUserHasEmailFalse(): void
    {
        $userId = UserId::fromString(Str::uuid()->toString());

        $user = User::register(
            $userId,
            Email::fromString('username@test.com'),
            HashedPassword::fromHash('password'),
        );

        $this->assertFalse($user->hasEmail(Email::fromString('other_username@test.com')));
    }
}
