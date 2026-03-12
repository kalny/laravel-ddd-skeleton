<?php

namespace App\Application\Services;

interface EventDispatcher
{
    public function dispatch(object $event): void;
}
