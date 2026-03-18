<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Common\Email;
use App\Domain\Common\Money;
use App\Domain\Common\UuidId;
use App\Domain\User\Exceptions\UserNotFoundException;
use App\Domain\User\HashedPassword;
use App\Domain\User\Repository\UserRepository;
use App\Domain\User\User;
use App\Domain\User\UserId;
use App\Domain\User\UserName;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use App\Infrastructure\Services\ReflectionService;
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
            'name' => UserName::fromString($userModel->name),
            'email' => Email::fromString($userModel->email),
            'password' => HashedPassword::fromHash($userModel->password),
            'balance' => Money::fromInteger($userModel->balance),
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

        $balance =  $this->reflectionService->getValue($user, 'balance');

        $userModel->id = $this->reflectionService->getValue($user->id(), 'uuid')->getValue();
        $userModel->name = $this->reflectionService->getValue($user, 'name')->getValue();
        $userModel->email = $this->reflectionService->getValue($user, 'email')->getValue();
        $userModel->password = $this->reflectionService->getValue($user, 'password')->getValue();
        $userModel->balance = $this->reflectionService->getValue($balance, 'amount');

        $userModel->saveOrFail();
    }
}
