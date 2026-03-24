<?php

namespace Tests\Unit\Identity\Application\UseCases\Commands\ChangeUserPassword;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Application\UseCases\Commands\ChangeUserPassword\ChangeUserPasswordCommand;
use App\Identity\Application\UseCases\Commands\ChangeUserPassword\ChangeUserPasswordCommandHandler;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Events\UserPasswordChanged;
use App\Identity\Domain\User\HashedPassword;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use Mockery;
use PHPUnit\Framework\TestCase;

class ChangeUserPasswordCommandHandlerTest extends TestCase
{
    public function testHandleSuccessfully(): void
    {
        $userId = 'user-id';
        $hashedPassword = password_hash('password', PASSWORD_BCRYPT);

        $newPlainPassword = 'password';
        $newHashedPassword = password_hash('password', PASSWORD_BCRYPT);

        $user = User::register(
            id: UserId::fromString($userId),
            email: Email::fromString('username@gmail.com'),
            password: HashedPassword::fromHash($hashedPassword),
        );
        $user->releaseEvents();

        $passwordHasherMock = Mockery::mock(PasswordHasher::class);
        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $passwordHasherMock
            ->shouldReceive('hash')
            ->once()
            ->with(Mockery::on(function (PlainPassword $password) use ($newPlainPassword) {
                return $password->value() === $newPlainPassword;
            }))
            ->andReturn(HashedPassword::fromHash($newHashedPassword));

        $userRepositoryMock
            ->shouldReceive('get')
            ->once()
            ->with(Mockery::on(function (UserId $id) use ($userId) {
                return $id->equals(UserId::fromString($userId));
            }))
            ->andReturn($user);

        $userRepositoryMock
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (User $u) use ($userId) {
                return $u->id()->equals(UserId::fromString($userId));
            }));

        $handler = new ChangeUserPasswordCommandHandler($passwordHasherMock, $userRepositoryMock);
        $result = $handler->handle(new ChangeUserPasswordCommand(
            id: $userId,
            password: $newPlainPassword
        ));

        $events = $result->events();

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertInstanceOf(UserPasswordChanged::class, $event);
        $this->assertTrue($event->id->equals(UserId::fromString($userId)));
    }

    public function testHandleSuccessfullyWithSamePassword(): void
    {
        $userId = 'user-id';
        $plainPassword = 'password';
        $hashedPassword = password_hash('password', PASSWORD_BCRYPT);

        $user = User::register(
            id: UserId::fromString($userId),
            email: Email::fromString('username@gmail.com'),
            password: HashedPassword::fromHash($hashedPassword),
        );
        $user->releaseEvents();

        $passwordHasherMock = Mockery::mock(PasswordHasher::class);
        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $passwordHasherMock
            ->shouldReceive('hash')
            ->once()
            ->with(Mockery::on(function (PlainPassword $password) use ($plainPassword) {
                return $password->value() === $plainPassword;
            }))
            ->andReturn(HashedPassword::fromHash($hashedPassword));

        $userRepositoryMock
            ->shouldReceive('get')
            ->once()
            ->with(Mockery::on(function (UserId $id) use ($userId) {
                return $id->equals(UserId::fromString($userId));
            }))
            ->andReturn($user);

        $userRepositoryMock
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (User $u) use ($userId) {
                return $u->id()->equals(UserId::fromString($userId));
            }));

        $handler = new ChangeUserPasswordCommandHandler($passwordHasherMock, $userRepositoryMock);
        $result = $handler->handle(new ChangeUserPasswordCommand(
            id: $userId,
            password: $plainPassword
        ));

        $events = $result->events();

        $this->assertCount(0, $events);
    }
}
