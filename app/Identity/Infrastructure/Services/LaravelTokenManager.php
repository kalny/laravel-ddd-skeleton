<?php

namespace App\Identity\Infrastructure\Services;

use App\Identity\Application\Services\TokenManager;
use App\Identity\Domain\User\Exceptions\UserNotFoundException;
use App\Identity\Domain\User\UserId;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Support\Facades\Auth;

class LaravelTokenManager implements TokenManager
{
    public function create(UserId $userId): string
    {
        $userModel = User::query()
            ->where('id', $userId->value())
            ->first();

        if (!$userModel) {
            throw new UserNotFoundException();
        }

        return $userModel->createToken('api_token')->plainTextToken;
    }

    public function delete(): void
    {
        $userModel = Auth::user();

        if (!$userModel) {
            return;
        }

        $userModel->currentAccessToken()->delete();
    }
}
