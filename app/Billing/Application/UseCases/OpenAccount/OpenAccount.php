<?php

namespace App\Billing\Application\UseCases\OpenAccount;

interface OpenAccount
{
    public function handle(OpenAccountCommand $command): OpenAccountResult;
}
