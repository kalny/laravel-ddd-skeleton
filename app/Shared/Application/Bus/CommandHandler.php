<?php

namespace App\Shared\Application\Bus;

interface CommandHandler
{
    public function handle(Command $command): CommandResult;
}
