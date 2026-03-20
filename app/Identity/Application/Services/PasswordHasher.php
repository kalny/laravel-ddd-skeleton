<?php

namespace App\Identity\Application\Services;

use App\Identity\Domain\User\HashedPassword;
use App\Identity\Domain\User\PlainPassword;

interface PasswordHasher
{
    public function hash(PlainPassword $password): HashedPassword;
    public function check(PlainPassword $plainPassword, HashedPassword $hashedPassword): bool;
}
