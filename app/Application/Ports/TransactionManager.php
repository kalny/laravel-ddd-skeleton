<?php

namespace App\Application\Ports;

interface TransactionManager
{
    public function transactional(callable $operation): mixed;
}
