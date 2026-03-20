<?php

namespace App\Identity\Infrastructure\Persistence\Eloquent\Repositories;

use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\Exceptions\UserNotFoundException;
use App\Identity\Domain\User\HashedPassword;
use App\Identity\Domain\User\Repositories\UserRepository;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use App\Shared\Domain\ValueObjects\UuidId;
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
            ->where('email', $email->getValue())
            ->exists();
    }

    public function get(UserId $id): User
    {
        /** @var UuidId $userUuidId */
        $userUuidId = $this->reflectionService->getValue($id, 'uuid');

        $userModel = UserModel::query()
            ->where('id', $userUuidId->getValue())
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
        /** @var UuidId $userUuidId */
        $userUuidId = $this->reflectionService->getValue($user->id(), 'uuid');

        $userModel = UserModel::query()
            ->where('id', $userUuidId->getValue())
            ->first();

        if (!$userModel) {
            $userModel = new UserModel();
        }

        $userModel->id = $this->reflectionService->getValue($user->id(), 'uuid')->getValue();
        $userModel->email = $this->reflectionService->getValue($user, 'email')->getValue();
        $userModel->password = $this->reflectionService->getValue($user, 'password')->getValue();

        $userModel->saveOrFail();
    }
}
