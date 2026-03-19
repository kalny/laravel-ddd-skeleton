<?php

namespace App\Shared\Infrastructure\Services;

use App\Shared\Application\Services\EventDispatcher;

class LaravelEventDispatcher implements EventDispatcher
{
    public function dispatch(object $event): void
    {
        event($event);
    }
}
