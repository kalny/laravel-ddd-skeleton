<?php

namespace App\Identity\Application\UseCases\LoginUser;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Application\Services\TokenManager;
use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Exceptions\InvalidCredentialsException;
use App\Identity\Domain\User\PlainPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\UserId;

final class LoginUserHandler
{
    public function __construct(
        private readonly PasswordHasher $hasher,
        private readonly TokenManager $tokenManager,
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
            email: $command->email,
            token: $this->tokenManager->create(UserId::fromString($user->id()->value()))
        );
    }
}
