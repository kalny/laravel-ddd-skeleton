<?php

namespace App\Application\UseCase\ChangeUserName;

use App\Application\Ports\TransactionManager;
use App\Application\Services\EventDispatcher;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\UserId;
use App\Domain\User\UserName;

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
