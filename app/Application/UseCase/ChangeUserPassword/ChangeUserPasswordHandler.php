<?php

namespace App\Application\UseCase\ChangeUserPassword;

use App\Application\Services\TransactionManager;
use App\Application\Services\EventDispatcher;
use App\Application\Services\PasswordHasher;
use App\Domain\User\PlainPassword;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\UserId;

final class ChangeUserPasswordHandler
{
    public function __construct(
        private readonly TransactionManager $transactionManager,
        private readonly EventDispatcher $dispatcher,
        private readonly PasswordHasher $hasher,
        private readonly UserRepository $users
    ){
    }

    public function handle(ChangeUserPasswordCommand $command): void
    {
        $user = $this->users->get(UserId::fromString($command->id));

        $user->changePassword($this->hasher->hash(PlainPassword::fromString($command->password)));

        $this->transactionManager->transactional(function () use ($user) {
            $this->users->save($user);

            foreach ($user->releaseEvents() as $event) {
                $this->dispatcher->dispatch($event);
            }
         });
    }
}
