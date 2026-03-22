<?php

namespace App\Billing\Application\UseCases\Commands\OpenAccount;

interface OpenAccount
{
    public function handle(OpenAccountCommand $command): void;
}
