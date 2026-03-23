<?php

namespace App\Billing\Application\UseCases\Commands\OpenAccount;

use App\Shared\Application\Bus\CommandResult;

interface OpenAccount
{
    public function handle(OpenAccountCommand $command): CommandResult;
}
