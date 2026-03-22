<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\CommandBus;
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
    public function dispatch(object $command): void
    {
        $handlerClass = $this->resolveHandler($command);

        $handler = $this->container->make($handlerClass);

        $handler->handle($command);
    }

    /**
     * @throws BindingResolutionException
     */
    public function dispatchWithReturn(object $command): mixed
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
