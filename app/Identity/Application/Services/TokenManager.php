<?php

namespace App\Identity\Application\Services;

use App\Identity\Domain\User\UserId;

interface TokenManager
{
    public function create(UserId $userId): string;
    public function delete(): void;
}
