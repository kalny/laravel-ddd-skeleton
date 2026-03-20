<?php

namespace App\Identity\Application\UseCases\RegisterUser;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Domain\Common\Email;
use App\Identity\Domain\User\Exceptions\UserAlreadyExistsException;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use App\Identity\Domain\User\UserName;
use App\Shared\Application\Services\EventDispatcher;
use App\Shared\Application\Services\IdGenerator;
use App\Shared\Application\Services\TransactionManager;

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
