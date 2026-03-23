<?php

namespace App\Providers;

use App\Shared\Application\Bus\CommandBus;
use App\Shared\Application\Bus\EventBus;
use App\Shared\Application\Bus\QueryBus;
use App\Shared\Infrastructure\Bus\LaravelCommandBus;
use App\Shared\Infrastructure\Bus\LaravelEventBus;
use App\Shared\Infrastructure\Bus\LaravelQueryBus;
use App\Shared\Infrastructure\Bus\Middlewares\TransactionMiddleware;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(QueryBus::class, LaravelQueryBus::class);
        $this->app->bind(EventBus::class, LaravelEventBus::class);
        $this->app->bind(CommandBus::class, function($app) {
            return new LaravelCommandBus($app->make(Container::class), [
                $app->make(TransactionMiddleware::class)
            ]);
        });
    }
}
