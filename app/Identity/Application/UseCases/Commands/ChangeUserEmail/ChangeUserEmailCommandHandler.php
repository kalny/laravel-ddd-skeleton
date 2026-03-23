<?php

namespace App\Identity\Application\UseCases\Commands\ChangeUserEmail;

use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Exceptions\EmailAlreadyTakenException;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\UserId;
use App\Shared\Application\Bus\CommandResult;

final class ChangeUserEmailCommandHandler
{
    public function __construct(
        private readonly UserRepository $users
    ){
    }

    public function handle(ChangeUserEmailCommand $command): CommandResult
    {
        $user = $this->users->get(UserId::fromString($command->id));

        $email = Email::fromString($command->email);

        if ($this->users->existsByEmail($email) && !$user->hasEmail($email)) {
            throw EmailAlreadyTakenException::withValue($command->email);
        }

        $user->changeEmail($email);

        $this->users->save($user);

        return CommandResult::success(null, $user->releaseEvents());
    }
}
