<?php

namespace App\Identity\Application\Services;

interface TokenManager
{
    public function create(string $userId): string;
    public function delete(): void;
}
