<?php

namespace App\Identity\Infrastructure\Services;

use App\Identity\Application\Services\PasswordHasher;
use App\Identity\Domain\User\HashedPassword;
use App\Identity\Domain\User\PlainPassword;
use Illuminate\Support\Facades\Hash;

class LaravelPasswordHasher implements PasswordHasher
{
    public function hash(PlainPassword $password): HashedPassword
    {
        $hash = Hash::make($password->getValue());

        return HashedPassword::fromHash($hash);
    }
}
