<?php

namespace App\Application\UseCase\RegisterUser;

use App\Application\Ports\TransactionManager;
use App\Application\Services\EventDispatcher;
use App\Application\Services\IdGenerator;
use App\Application\Services\PasswordHasher;
use App\Domain\Common\Email;
use App\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Domain\User\PlainPassword;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserName;

final class RegisterUserHandler
{
    public function __construct(
        private readonly IdGenerator $idGenerator,
        private readonly PasswordHasher $hasher,
        private readonly TransactionManager $transactionManager,
        private readonly EventDispatcher $dispatcher,
        private readonly UserRepository $users,
    ) {
    }

    public function handle(RegisterUserCommand $command): RegisterUserResult
    {
        if ($this->users->existsByEmail(Email::fromString($command->email))) {
            throw UserAlreadyExistsException::withValue($command->email);
        }

        $id = $this->idGenerator->generate();

        $user = User::register(
            UserId::fromString($id),
            UserName::fromString($command->name),
            Email::fromString($command->email),
            $this->hasher->hash(PlainPassword::fromString($command->password))
        );

        return $this->transactionManager->transactional(function () use ($user, $command, $id) {
            $this->users->save($user);

            foreach ($user->releaseEvents() as $event) {
                $this->dispatcher->dispatch($event);
            }

            return new RegisterUserResult(
                id: $id,
                name: $command->name,
                email: $command->email
            );
        });
    }
}
