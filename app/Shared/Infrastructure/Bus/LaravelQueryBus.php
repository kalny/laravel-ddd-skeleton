<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\Query;
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
    public function ask(Query $query): mixed
    {
        $handlerClass = $this->resolveHandler($query);

        $handler = $this->container->make($handlerClass);

        return $handler->handle($query);
    }

    private function resolveHandler(Query $query): string
    {
        return get_class($query) . 'Handler';
    }
}
