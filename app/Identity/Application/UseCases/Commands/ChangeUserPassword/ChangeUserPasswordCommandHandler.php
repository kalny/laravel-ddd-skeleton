<?php

namespace App\Identity\Application\UseCases\Commands\ChangeUserPassword;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\UserId;
use App\Shared\Application\Bus\Command;
use App\Shared\Application\Bus\CommandHandler;
use App\Shared\Application\Bus\CommandResult;

final class ChangeUserPasswordCommandHandler implements CommandHandler
{
    public function __construct(
        private readonly PasswordHasher $hasher,
        private readonly UserRepository $users
    ){
    }

    public function handle(Command $command): CommandResult
    {
        /** @var ChangeUserPasswordCommand $command */

        $user = $this->users->get(UserId::fromString($command->id));

        $user->changePassword($this->hasher->hash(PlainPassword::fromString($command->password)));

        $this->users->save($user);

        return CommandResult::success(null, $user->releaseEvents());
    }
}
