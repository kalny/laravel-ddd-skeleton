<?php

namespace Tests\Unit\Identity\Application\UseCases\Commands\ChangeUserEmail;

use App\Identity\Application\UseCases\Commands\ChangeUserEmail\ChangeUserEmailCommand;
use App\Identity\Application\UseCases\Commands\ChangeUserEmail\ChangeUserEmailCommandHandler;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Events\UserEmailChanged;
use App\Identity\Domain\User\Exceptions\EmailAlreadyTakenException;
use App\Identity\Domain\User\HashedPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mockery;
use Tests\TestCase;

class ChangeUserEmailCommandHandlerTest extends TestCase
{
    public function testHandleSuccessfully(): void
    {
        $userId = Str::uuid()->toString();
        $user = User::register(
            id: UserId::fromString($userId),
            email: Email::fromString('username@gmail.com'),
            password: HashedPassword::fromHash(Hash::make('password'))
        );
        $user->releaseEvents();

        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $userRepositoryMock
            ->shouldReceive('get')
            ->once()
            ->with(Mockery::on(function (UserId $id) use ($userId) {
                return $id->equals(UserId::fromString($userId));
            }))
            ->andReturn($user);

        $userRepositoryMock
            ->shouldReceive('existsByEmail')
            ->once()
            ->with(Mockery::on(function (Email $email) {
                return $email->equals(Email::fromString('new_email@gmail.com'));
            }))
            ->andReturn(false);

        $userRepositoryMock
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function (User $user) {
                return $user->hasEmail(Email::fromString('new_email@gmail.com'));
            }));

        $handler = new ChangeUserEmailCommandHandler($userRepositoryMock);
        $result = $handler->handle(new ChangeUserEmailCommand(
            id: $userId,
            email: 'new_email@gmail.com'
        ));

        $events = $result->events();

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertInstanceOf(UserEmailChanged::class, $event);
        $this->assertTrue($event->id->equals(UserId::fromString($userId)));
        $this->assertTrue($event->email->equals(Email::fromString('new_email@gmail.com')));
    }

    public function testHandleSuccessfullyWithSameEmail(): void
    {
        $userId = Str::uuid()->toString();
        $user = User::register(
            id: UserId::fromString($userId),
            email: Email::fromString('username@gmail.com'),
            password: HashedPassword::fromHash(Hash::make('password'))
        );
        $user->releaseEvents();

        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $userRepositoryMock
            ->shouldReceive('get')
            ->once()
            ->with(Mockery::on(function (UserId $id) use ($userId) {
                return $id->equals(UserId::fromString($userId));
            }))
            ->andReturn($user);

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
            ->with(Mockery::on(function (User $user) {
                return $user->hasEmail(Email::fromString('username@gmail.com'));
            }));

        $handler = new ChangeUserEmailCommandHandler($userRepositoryMock);
        $result = $handler->handle(new ChangeUserEmailCommand(
            id: $userId,
            email: 'username@gmail.com'
        ));

        $events = $result->events();

        $this->assertCount(0, $events);
    }

    public function testHandleSuccessfullyWithSameEmailExistedInRepository(): void
    {
        $userId = Str::uuid()->toString();
        $user = User::register(
            id: UserId::fromString($userId),
            email: Email::fromString('username@gmail.com'),
            password: HashedPassword::fromHash(Hash::make('password'))
        );
        $user->releaseEvents();

        $userRepositoryMock = Mockery::mock(UserRepository::class);

        $userRepositoryMock
            ->shouldReceive('get')
            ->once()
            ->with(Mockery::on(function (UserId $id) use ($userId) {
                return $id->equals(UserId::fromString($userId));
            }))
            ->andReturn($user);

        $userRepositoryMock
            ->shouldReceive('existsByEmail')
            ->once()
            ->with(Mockery::on(function (Email $email) {
                return $email->equals(Email::fromString('new_username@gmail.com'));
            }))
            ->andReturn(true);

        $userRepositoryMock
            ->shouldReceive('save')
            ->never();

        $this->expectException(EmailAlreadyTakenException::class);

        $handler = new ChangeUserEmailCommandHandler($userRepositoryMock);
        $handler->handle(new ChangeUserEmailCommand(
            id: $userId,
            email: 'new_username@gmail.com'
        ));
    }
}
