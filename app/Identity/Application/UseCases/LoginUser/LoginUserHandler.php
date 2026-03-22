<?php

namespace App\Identity\Application\UseCases\LoginUser;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Exceptions\InvalidCredentialsException;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;

final class LoginUserHandler
{
    public function __construct(
        private readonly PasswordHasher $hasher,
        private readonly UserRepository $users,
    ) {
    }

    public function handle(LoginUserCommand $command): LoginUserResult
    {
        $user = $this->users->findByEmail(Email::fromString($command->email));

        if (!$user || !$this->hasher->check(PlainPassword::fromString($command->password), $user->password())) {
            throw new InvalidCredentialsException();
        }

        return new LoginUserResult(
            id: $user->id()->value(),
            email: $command->email
        );
    }
}
