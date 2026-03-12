<?php

namespace App\Infrastructure\Services;

use App\Application\Services\EventDispatcher;

class LaravelEventDispatcher implements EventDispatcher
{
    public function dispatch(object $event): void
    {
        event($event);
    }
}
