<?php

namespace App\Identity\Application\UseCases\Queries\GetUser;

use App\Identity\Application\DTO\UserDTO;
use App\Identity\Domain\User\Exceptions\UserNotFoundException;
use App\Identity\Infrastructure\Persistence\Eloquent\Models\User;

class GetUserQueryHandler
{
    public function handle(GetUserQuery $query): UserDTO
    {
        $userModel = User::query()
            ->where('id', $query->id)
            ->first();

        if (!$userModel) {
            throw new UserNotFoundException();
        }

        return new UserDTO(
            id: $userModel->id,
            email: $userModel->email
        );
    }
}
