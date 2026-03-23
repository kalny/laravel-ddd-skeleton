<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\CommandResult;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;

class LaravelCommandBus implements CommandBus
{
    public function __construct(private Container $container)
    {
    }

    /**
     * @throws BindingResolutionException
     */
    public function dispatch(object $command): CommandResult
    {
        $handlerClass = $this->resolveHandler($command);

        $handler = $this->container->make($handlerClass);

        return $handler->handle($command);
    }

    private function resolveHandler(object $command): string
    {
        return get_class($command) . 'Handler';
    }
}
