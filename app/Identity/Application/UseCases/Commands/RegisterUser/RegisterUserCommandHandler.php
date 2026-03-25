<?php

namespace App\Identity\Application\UseCases\Commands\RegisterUser;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use App\Shared\Application\Bus\Command;
use App\Shared\Application\Bus\CommandHandler;
use App\Shared\Application\Bus\CommandResult;
use App\Shared\Application\Services\IdGenerator;

final class RegisterUserCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly IdGenerator $idGenerator,
        private readonly PasswordHasher $hasher,
        private readonly UserRepository $users,
    ) {
    }

    public function handle(Command $command): CommandResult
    {
        /** @var RegisterUserCommand $command */

        if ($this->users->existsByEmail(Email::fromString($command->email))) {
            throw UserAlreadyExistsException::withValue($command->email);
        }

        $id = $this->idGenerator->generate();

        $user = User::register(
            UserId::fromString($id),
            Email::fromString($command->email),
            $this->hasher->hash(PlainPassword::fromString($command->password))
        );

        $this->users->save($user);

        return CommandResult::success(UserId::fromString($id), $user->releaseEvents());
    }
}
