<?php

namespace App\Shared\Infrastructure\Services;

use App\Shared\Application\Services\TransactionManager;
use Illuminate\Support\Facades\DB;
use Throwable;

class LaravelTransactionManager implements TransactionManager
{
    /**
     * @throws Throwable
     */
    public function transactional(callable $operation): mixed
    {
        return DB::transaction($operation);
    }
}
