<?php

namespace App\Billing\Domain\Account\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class AccountNotFoundException extends DomainExceptionBase
{
    public function __construct(string $message = 'Account not found')
    {
        parent::__construct($message);
    }
}
