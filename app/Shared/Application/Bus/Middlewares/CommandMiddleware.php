<?php

namespace App\Shared\Application\Bus\Middlewares;

use App\Shared\Application\Bus\CommandResult;
use Closure;

interface CommandMiddleware
{
    public function handle(object $command, Closure $next): CommandResult;
}
