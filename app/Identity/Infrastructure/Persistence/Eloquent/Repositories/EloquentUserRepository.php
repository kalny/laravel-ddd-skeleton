<?php

namespace App\Identity\Infrastructure\Persistence\Eloquent\Repositories;

use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Exceptions\UserNotFoundException;
use App\Identity\Domain\User\HashedPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use App\Shared\Infrastructure\Services\ReflectionService;
use Throwable;

class EloquentUserRepository implements UserRepository
{
    public function __construct(private readonly ReflectionService $reflectionService)
    {
    }

    public function existsByEmail(Email $email): bool
    {
       return UserModel::query()
            ->where('email', $email->value())
            ->exists();
    }

    public function get(UserId $id): User
    {
        $userModel = UserModel::query()
            ->where('id', $id->value())
            ->first();

        if (!$userModel) {
            throw new UserNotFoundException('User not found');
        }

        /** @var User $userEntity */
        $userEntity = $this->reflectionService->createObject(User::class, [
            'id' => UserId::fromString($userModel->id),
            'email' => Email::fromString($userModel->email),
            'password' => HashedPassword::fromHash($userModel->password),
        ]);

        return $userEntity;
    }

    /**
     * @throws Throwable
     */
    public function save(User $user): void
    {
        $userModel = UserModel::query()
            ->where('id', $user->id()->value())
            ->first();

        if (!$userModel) {
            $userModel = new UserModel();
        }

        $userModel->id = $user->id()->value();
        $userModel->email = $this->reflectionService->getValue($user, 'email')->value();
        $userModel->password = $this->reflectionService->getValue($user, 'password')->value();

        $userModel->saveOrFail();
    }

    public function findByEmail(Email $email): ?User
    {
        $userModel = UserModel::query()
            ->where('email', $email->value())
            ->first();

        if (!$userModel) {
            return null;
        }

        /** @var User $userEntity */
        $userEntity = $this->reflectionService->createObject(User::class, [
            'id' => UserId::fromString($userModel->id),
            'email' => Email::fromString($userModel->email),
            'password' => HashedPassword::fromHash($userModel->password),
        ]);

        return $userEntity;
    }
}
