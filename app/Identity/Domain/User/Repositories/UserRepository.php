<?php

namespace App\Identity\Domain\User\Repositories;

use App\Identity\Domain\User\Email;
use App\Identity\Domain\User\User;
use App\Identity\Domain\User\UserId;

interface UserRepository
{
    public function existsByEmail(Email $email): bool;
    public function get(UserId $id): User;
    public function save(User $user): void;
}
