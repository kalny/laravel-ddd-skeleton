<?php

namespace App\Domain\User\Repository;

use App\Domain\Common\Email;
use App\Domain\User\User;
use App\Domain\User\UserId;

interface UserRepository
{
    public function existsByEmail(Email $email): bool;
    public function get(UserId $id): User;
    public function save(User $user): void;
}
