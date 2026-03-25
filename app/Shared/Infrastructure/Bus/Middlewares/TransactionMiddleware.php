<?php

namespace App\Shared\Infrastructure\Bus\Middlewares;

use App\Shared\Application\Bus\Command;
use App\Shared\Application\Bus\CommandResult;
use App\Shared\Application\Bus\Middlewares\CommandMiddleware;
use Closure;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionMiddleware implements CommandMiddleware
{
    /**
     * @throws Throwable
     */
    public function handle(Command $command, Closure $next): CommandResult
    {
        return DB::transaction(function () use ($command, $next) {
            return $next($command);
        });
    }
}
