<?php

namespace App\Application\Services;

use App\Domain\User\HashedPassword;
use App\Domain\User\PlainPassword;

interface PasswordHasher
{
    public function hash(PlainPassword $password): HashedPassword;
}
