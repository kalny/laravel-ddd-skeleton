<?php

namespace Tests\Unit\Identity\Application\UseCases\Commands\RegisterUser;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Application\UseCases\Commands\RegisterUser\RegisterUserCommand;
use App\Identity\Application\UseCases\Commands\RegisterUser\RegisterUserCommandHandler;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Events\UserRegistered;
use App\Identity\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Identity\Domain\User\HashedPassword;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use App\Shared\Application\Services\IdGenerator;
use Mockery;
use PHPUnit\Framework\TestCase;

class RegisterUserCommandHandlerTest extends TestCase
{
    public function testHandleSuccessfully(): void
    {
        $userId = 'user-id';
        $plainPassword = 'password';
        $hashedPassword = password_hash($plainPassword, PASSWORD_BCRYPT);

        $idGeneratorMock = Mockery::mock(IdGenerator::class);
        $passwordHasherMock = Mockery::mock(PasswordHasher::class);
        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $userRepositoryMock
            ->shouldReceive('existsByEmail')
            ->once()
            ->with(Mockery::on(function (Email $email) {
                return $email->equals(Email::fromString('username@gmail.com'));
            }))
            ->andReturn(false);

        $userRepositoryMock
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (User $u) use ($userId) {
                return $u->id()->equals(UserId::fromString($userId));
            }));

        $idGeneratorMock
            ->shouldReceive('generate')
            ->once()
            ->andReturn($userId);

        $passwordHasherMock
            ->shouldReceive('hash')
            ->once()
            ->with(Mockery::on(function (PlainPassword $password) use ($plainPassword) {
                return $password->value() === $plainPassword;
            }))
            ->andReturn(HashedPassword::fromHash($hashedPassword));

        $handler = new RegisterUserCommandHandler($idGeneratorMock, $passwordHasherMock, $userRepositoryMock);
        $result = $handler->handle(new RegisterUserCommand(
            email: 'username@gmail.com',
            password: $plainPassword,
        ));

        $this->assertTrue($result->payload()->equals(UserId::fromString($userId)));

        $events = $result->events();

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertInstanceOf(UserRegistered::class, $event);
        $this->assertTrue($event->id->equals(UserId::fromString($userId)));
        $this->assertTrue($event->email->equals(Email::fromString('username@gmail.com')));
    }

    public function testHandleUserAlreadyExists(): void
    {
        $plainPassword = 'password';

        $idGeneratorMock = Mockery::mock(IdGenerator::class);
        $passwordHasherMock = Mockery::mock(PasswordHasher::class);
        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $userRepositoryMock
            ->shouldReceive('existsByEmail')
            ->once()
            ->with(Mockery::on(function (Email $email) {
                return $email->equals(Email::fromString('username@gmail.com'));
            }))
            ->andReturn(true);

        $userRepositoryMock
            ->shouldReceive('save')
            ->never();

        $this->expectException(UserAlreadyExistsException::class);

        $handler = new RegisterUserCommandHandler($idGeneratorMock, $passwordHasherMock, $userRepositoryMock);
        $handler->handle(new RegisterUserCommand(
            email: 'username@gmail.com',
            password: $plainPassword,
        ));
    }
}
