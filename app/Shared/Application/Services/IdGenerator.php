<?php

namespace App\Shared\Application\Services;

interface IdGenerator
{
    public function generate(): string;
}
