<?php

namespace App\Shared\Application\Bus;

interface CommandBus
{
    public function dispatch(Command $command): CommandResult;
}
