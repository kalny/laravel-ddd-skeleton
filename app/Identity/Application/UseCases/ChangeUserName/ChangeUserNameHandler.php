<?php

namespace App\Identity\Application\UseCases\ChangeUserName;

use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\UserId;
use App\Identity\Domain\User\UserName;
use App\Shared\Application\Services\EventDispatcher;
use App\Shared\Application\Services\TransactionManager;

final class ChangeUserNameHandler
{
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly EventDispatcher $dispatcher,
        private readonly UserRepository $users
    ){
    }

    public function handle(ChangeUserNameCommand $command): void
    {
        $user = $this->users->get(UserId::fromString($command->id));

        $user->changeName(UserName::fromString($command->name));

        $this->transactionManager->transactional(function () use ($user) {
            $this->users->save($user);

            foreach ($user->releaseEvents() as $event) {
                $this->dispatcher->dispatch($event);
            }
         });
    }
}
