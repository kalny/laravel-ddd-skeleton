<?php

namespace Tests\Unit\Identity\Application\UseCases\Commands\LoginUser;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Application\UseCases\Commands\LoginUser\LoginUserCommand;
use App\Identity\Application\UseCases\Commands\LoginUser\LoginUserCommandHandler;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Exceptions\InvalidCredentialsException;
use App\Identity\Domain\User\HashedPassword;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use Mockery;
use PHPUnit\Framework\TestCase;

class LoginUserCommandHandlerTest extends TestCase
{
    public function testHandleSuccessfully(): void
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
            ->shouldReceive('check')
            ->once()
            ->with(Mockery::on(function (PlainPassword $password) use ($plainPassword) {
                return $password->value() === $plainPassword;
            }), Mockery::on(function (HashedPassword $password) use ($hashedPassword) {
                return $password->value() === $hashedPassword;
            }))
            ->andReturn(true);

        $userRepositoryMock
            ->shouldReceive('findByEmail')
            ->once()
            ->with(Mockery::on(function (Email $email) {
                return $email->equals(Email::fromString('username@gmail.com'));
            }))
            ->andReturn($user);

        $handler = new LoginUserCommandHandler($passwordHasherMock, $userRepositoryMock);
        $result = $handler->handle(new LoginUserCommand(
            email: 'username@gmail.com',
            password: $plainPassword
        ));

        $this->assertEquals($result->payload(), UserId::fromString($userId));
    }

    public function testHandleUserNotFound(): void
    {
        $plainPassword = 'password';

        $passwordHasherMock = Mockery::mock(PasswordHasher::class);
        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $userRepositoryMock
            ->shouldReceive('findByEmail')
            ->once()
            ->with(Mockery::on(function (Email $email) {
                return $email->equals(Email::fromString('username@gmail.com'));
            }))
            ->andReturn(null);

        $passwordHasherMock
            ->shouldReceive('check')
            ->never();

        $this->expectException(InvalidCredentialsException::class);

        $handler = new LoginUserCommandHandler($passwordHasherMock, $userRepositoryMock);
        $handler->handle(new LoginUserCommand(
            email: 'username@gmail.com',
            password: $plainPassword
        ));
    }

    public function testHandleInvalidCredentials(): void
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
            ->shouldReceive('check')
            ->once()
            ->with(Mockery::on(function (PlainPassword $password) use ($plainPassword) {
                return $password->value() === $plainPassword;
            }), Mockery::on(function (HashedPassword $password) use ($hashedPassword) {
                return $password->value() === $hashedPassword;
            }))
            ->andReturn(false);

        $userRepositoryMock
            ->shouldReceive('findByEmail')
            ->once()
            ->with(Mockery::on(function (Email $email) {
                return $email->equals(Email::fromString('username@gmail.com'));
            }))
            ->andReturn($user);

        $this->expectException(InvalidCredentialsException::class);

        $handler = new LoginUserCommandHandler($passwordHasherMock, $userRepositoryMock);
        $handler->handle(new LoginUserCommand(
            email: 'username@gmail.com',
            password: $plainPassword
        ));
    }
}
