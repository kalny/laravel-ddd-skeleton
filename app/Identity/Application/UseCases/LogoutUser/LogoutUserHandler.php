<?php

namespace App\Identity\Application\UseCases\LogoutUser;

use App\Identity\Application\Services\TokenManager;

class LogoutUserHandler
{
    public function __construct(private readonly TokenManager $tokenManager)
    {
    }

    public function handle(): void
    {
        $this->tokenManager->delete();
    }
}
