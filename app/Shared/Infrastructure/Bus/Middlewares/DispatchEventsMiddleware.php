<?php

namespace App\Shared\Infrastructure\Bus\Middlewares;

use App\Shared\Application\Bus\Command;
use App\Shared\Application\Bus\CommandResult;
use App\Shared\Application\Bus\EventBus;
use App\Shared\Application\Bus\Middlewares\CommandMiddleware;
use Closure;
use Throwable;

class DispatchEventsMiddleware implements CommandMiddleware
{
    public function __construct(private EventBus $eventBus)
    {
    }

    /**
     * @throws Throwable
     */
    public function handle(Command $command, Closure $next): CommandResult
    {
        $result = $next($command);

        foreach ($result->events() as $event) {
            $this->eventBus->dispatch($event);
        }

        return $result;
    }
}
