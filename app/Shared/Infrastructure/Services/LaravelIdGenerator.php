<?php

namespace App\Shared\Infrastructure\Services;

use App\Shared\Application\Services\IdGenerator;
use Illuminate\Support\Str;

class LaravelIdGenerator implements IdGenerator
{
    public function generate(): string
    {
        return Str::uuid()->toString();
    }
}
