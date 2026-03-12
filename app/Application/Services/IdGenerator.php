<?php

namespace App\Application\Services;

interface IdGenerator
{
    public function generate(): string;
}
