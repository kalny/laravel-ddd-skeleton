<?php

namespace App\Identity\Application\UseCases\ChangeUserPassword;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\UserId;
use App\Shared\Application\Services\EventDispatcher;
use App\Shared\Application\Services\TransactionManager;

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
