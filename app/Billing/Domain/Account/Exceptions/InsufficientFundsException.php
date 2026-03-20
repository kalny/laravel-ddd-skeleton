<?php

namespace App\Billing\Domain\Account\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class InsufficientFundsException extends DomainExceptionBase
{
    public function __construct(string $message = 'Insufficient funds')
    {
        parent::__construct($message);
    }
}
