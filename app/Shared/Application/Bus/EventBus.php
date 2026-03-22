<?php

namespace App\Shared\Application\Bus;

interface EventBus
{
    public function dispatch(object $event): void;
}
