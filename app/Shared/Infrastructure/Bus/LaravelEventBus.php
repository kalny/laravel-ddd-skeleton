<?php

namespace App\Shared\Infrastructure\Bus;

use App\Shared\Application\Bus\EventBus;
use Illuminate\Contracts\Events\Dispatcher;

class LaravelEventBus implements EventBus
{
    public function __construct(private Dispatcher $dispatcher)
    {
    }

    public function dispatch(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
