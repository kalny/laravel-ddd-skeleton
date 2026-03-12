<?php

namespace App\Infrastructure\Services;

use App\Application\Services\IdGenerator;
use Illuminate\Support\Str;

class LaravelIdGenerator implements IdGenerator
{
    public function generate(): string
    {
        return Str::uuid()->toString();
    }
}
