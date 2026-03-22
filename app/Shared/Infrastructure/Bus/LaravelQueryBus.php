<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\QueryBus;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;

class LaravelQueryBus implements QueryBus
{
    public function __construct(private Container $container)
    {
    }

    /**
     * @throws BindingResolutionException
     */
    public function ask(object $query): mixed
    {
        $handlerClass = $this->resolveHandler($query);

        $handler = $this->container->make($handlerClass);

        return $handler->handle($query);
    }

    private function resolveHandler(object $command): string
    {
        return get_class($command) . 'Handler';
    }
}
