<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\CommandResult;
use App\Shared\Application\Bus\Middlewares\CommandMiddleware;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Container\Container;

class LaravelCommandBus implements CommandBus
{
    public function __construct(
        private Container $container,
        /** @var CommandMiddleware[] */
        private array $middlewares = []
    ) {
    }

    /**
     * @throws BindingResolutionException
     */
    public function dispatch(object $command): CommandResult
    {
        $handlerClass = $this->resolveHandler($command);

        $handler = $this->container->make($handlerClass);

        $pipeline = $this->buildPipeline($handler);

        return $pipeline($command);
    }

    private function buildPipeline(object $handler): Closure
    {
        $core = function (object $command) use ($handler): CommandResult {
            return $handler->handle($command);
        };

        foreach (array_reverse($this->middlewares) as $middleware) {
            $core = function (object $command) use ($middleware, $core): CommandResult {
                return $middleware->handle($command, $core);
            };
        }

        return $core;
    }

    private function resolveHandler(object $command): string
    {
        return get_class($command) . 'Handler';
    }
}
