<?php

namespace App\Infrastructure\Services;

use App\Application\Services\PasswordHasher;
use App\Domain\User\HashedPassword;
use App\Domain\User\PlainPassword;
use Illuminate\Support\Facades\Hash;

class LaravelPasswordHasher implements PasswordHasher
{
    public function hash(PlainPassword $password): HashedPassword
    {
        $hash = Hash::make($password->getValue());

        return HashedPassword::fromHash($hash);
    }
}
