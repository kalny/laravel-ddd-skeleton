<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Prompts\Concerns\Events;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withEvents(discover: [
        __DIR__ . '/../app/Identity/Infrastructure/DomainEventListeners',
        __DIR__ . '/../app/Identity/Infrastructure/IntegrationEventListeners',
        __DIR__ . '/../app/Billing/Infrastructure/DomainEventListeners',
        __DIR__ . '/../app/Billing/Infrastructure/IntegrationEventListeners',
    ])
    ->create();
