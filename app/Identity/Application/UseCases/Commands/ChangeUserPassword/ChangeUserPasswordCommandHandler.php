<?php

namespace App\Identity\Application\UseCases\Commands\ChangeUserPassword;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\UserId;
use App\Shared\Application\Bus\CommandResult;
use App\Shared\Application\Bus\EventBus;
use App\Shared\Application\Services\TransactionManager;

final class ChangeUserPasswordCommandHandler
{
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly EventBus $eventBus,
        private readonly PasswordHasher $hasher,
        private readonly UserRepository $users
    ){
    }

    public function handle(ChangeUserPasswordCommand $command): CommandResult
    {
        $user = $this->users->get(UserId::fromString($command->id));

        $user->changePassword($this->hasher->hash(PlainPassword::fromString($command->password)));

        $this->transactionManager->transactional(function () use ($user) {
            $this->users->save($user);

            foreach ($user->releaseEvents() as $event) {
                $this->eventBus->dispatch($event);
            }
         });

        return CommandResult::success();
    }
}
