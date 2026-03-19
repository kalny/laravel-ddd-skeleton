<?php

namespace App\Application\Services;

interface TransactionManager
{
    public function transactional(callable $operation): mixed;
}
