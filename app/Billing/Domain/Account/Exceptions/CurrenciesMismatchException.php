<?php

namespace App\Billing\Domain\Account\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class CurrenciesMismatchException extends DomainExceptionBase
{
    public function __construct(string $message = 'Currencies mismatch')
    {
        parent::__construct($message);
    }
}
