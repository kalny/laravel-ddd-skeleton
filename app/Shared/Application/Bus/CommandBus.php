<?php

namespace App\Shared\Application\Bus;

interface CommandBus
{
    public function dispatch(object $command): void;
    public function dispatchWithReturn(object $command): mixed;
}
