<?php

namespace App\Shared\Application\Services;

interface EventDispatcher
{
    public function dispatch(object $event): void;
}
