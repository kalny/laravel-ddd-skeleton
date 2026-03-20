<?php

namespace App\Billing\Domain\Account\Exceptions;

use App\Shared\Domain\Exceptions\DomainExceptionBase;

final class InvalidMoneyException extends DomainExceptionBase
{
    public function __construct(string $message = 'Invalid money value')
    {
        parent::__construct($message);
    }
}
