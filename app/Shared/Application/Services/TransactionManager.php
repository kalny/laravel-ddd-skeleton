<?php

namespace App\Shared\Application\Services;

interface TransactionManager
{
    public function transactional(callable $operation): mixed;
}
